<?php

namespace App\Filament\Resources\Vehicles\Pages;

use App\Filament\Actions\TurnVehicleOffAction;
use App\Filament\Resources\Vehicles\VehicleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\HtmlString;

class ViewVehicle extends ViewRecord
{
    protected static string $resource = VehicleResource::class;

    public function getHeading(): HtmlString
    {
        $vehicle = $this->record;
        $isOn = $vehicle->ignition_on;

        $statusColor = $isOn ? 'bg-green-700' : 'bg-gray-400';
        $statusText = $isOn ? 'Ligado' : 'Desligado';

        return new HtmlString(
            '<div class="flex items-center gap-3">
                <span>Visualizar Veículo</span>
                <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-full ' . $statusColor . ' text-white">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full rounded-full ' . ($isOn ? 'bg-green-300 animate-ping ' : 'bg-gray-300') . ' opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                    </span>
                    ' . $statusText . '
                </span>
            </div>'
        );
    }
    protected function getHeaderActions(): array
    {
        return [
            TurnVehicleOffAction::make(),
            EditAction::make(),
        ];
    }
}
