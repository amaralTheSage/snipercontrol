<?php

namespace App\Services;

use App\Models\Device;
use App\Models\TelemetryEvent;
use App\Models\Trip;
use App\Models\Warning;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class WarningDetectionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function checkForSuspiciousActivity(Device $device, TelemetryEvent $currentEvent, Trip $trip): void
    {
        $previousEvent = TelemetryEvent::where('trip_id', $trip->id)
            ->where('id', '<', $currentEvent->id)
            ->orderBy('recorded_at', 'desc')
            ->first();

        if (! $previousEvent) {
            return;
        }

        $this->checkFuelTheft($device, $previousEvent, $currentEvent);
        $this->checkUnexpectedStop($device, $previousEvent);
    }

    public function checkFuelTheft(Device $device, TelemetryEvent $previousEvent, TelemetryEvent $currentEvent): void
    {
        if ($previousEvent->fuel === null || $currentEvent->fuel === null) {
            Log::warning('Fuel data missing', ['vehicle_id' => $device->vehicle_id]);

            return;
        }

        $fuelDrop = $previousEvent->fuel - $currentEvent->fuel;
        $suspiciousDropPercent = 2;
        $criticalDropPercent = 3;

        if ($fuelDrop >= $suspiciousDropPercent) {
            $severity = $fuelDrop >= $criticalDropPercent ? 'high' : 'low';

            $existingWarning = Warning::where('vehicle_id', $device->vehicle_id)
                ->where('type', 'fuel_theft')
                ->whereNull('resolved_at')
                ->where('occurred_at', '>=', now()->subMinutes(20))
                ->first();

            if (! $existingWarning) {
                $warning = Warning::create([
                    'type' => 'fuel_theft',
                    'vehicle_id' => $device->vehicle_id,
                    'driver_id' => $device->vehicle->currentDriver?->id,
                    'description' => "Suspicious fuel drop: {$fuelDrop}%",
                    'latitude' => $currentEvent->lat,
                    'longitude' => $currentEvent->lng,
                    'severity' => $severity,
                    'occurred_at' => $currentEvent->recorded_at,
                ]);

                $colorMap = ['low' => 'warning', 'medium' => 'warning', 'high' => 'danger'];

                $device->company->notify(
                    Notification::make()
                        ->title("Possível furto de combustível no veículo {$device->vehicle->plate}")
                        ->body("Severidade: {$severity}")
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color($colorMap[$severity])
                        ->actions([
                            Action::make('view')
                                ->label('Ver Aviso')
                                ->url(route('filament.dash.resources.warnings.show', $warning)),
                        ])
                        ->toDatabase()
                );
            }
        }
    }

    public function checkUnexpectedStop(Device $device, TelemetryEvent $currentEvent): void
    {
        if ($currentEvent->lat === null || $currentEvent->lng === null) {
            return;
        }

        $stoppedDuration = TelemetryEvent::where('trip_id', $currentEvent->trip_id)
            ->where('lat', $currentEvent->lat)
            ->where('lng', $currentEvent->lng)
            ->where('recorded_at', '<=', $currentEvent->recorded_at)
            ->orderBy('recorded_at', 'desc')
            ->count() * 10; // Each event is 10 seconds apart

        $severity = $stoppedDuration > 60 ? 'high' : ($stoppedDuration > 300 ? 'medium' : 'low');

        $existingWarning = Warning::where('vehicle_id', $device->vehicle_id)
            ->where('type', 'unexpected_stop')
            ->whereNull('resolved_at')
            ->where('occurred_at', '>=', now()->subMinutes(10))
            ->first();

        if (! $existingWarning && $stoppedDuration > 20) { // Avisa se estiver parado por mais de 5 minutos (ajustar, no momento ta 20 segundos)
            $warning = Warning::create([
                'type' => 'unexpected_stop',
                'vehicle_id' => $device->vehicle_id,
                'driver_id' => $device->vehicle->currentDriver?->id,
                'latitude' => $currentEvent->lat,
                'longitude' => $currentEvent->lng,
                'severity' => $severity,
                'occurred_at' => $currentEvent->recorded_at,
            ]);

            $colorMap = ['low' => 'warning', 'medium' => 'warning', 'high' => 'danger'];

            $device->company->notify(
                Notification::make()
                    ->title("Possível parada inesperada do veículo {$device->vehicle->plate}")
                    ->body("Severidade: {$severity}")
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color($colorMap[$severity])
                    ->actions([
                        Action::make('view')
                            ->label('Ver Aviso')
                            ->url(route('filament.dash.resources.warnings.show', $warning)),
                    ])
                    ->toDatabase()
            );
        }
    }
}
