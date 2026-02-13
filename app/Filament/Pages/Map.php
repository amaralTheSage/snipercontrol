<?php

namespace App\Filament\Pages;

use App\Models\Driver;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class Map extends Page
{
    protected string $view = 'filament.pages.map';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $navigationLabel = 'Frota';

    protected static ?int $navigationSort = 2;

    protected Width|string|null $maxContentWidth = 'full';

    public $drivers = [];

    public function mount()
    {
        $this->drivers = $this->getDriversProperty();
    }

    public function getDriversProperty()
    {
        return Driver::with(['currentVehicle', 'currentVehicleDevice', 'trips'])->get()->map(function ($driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->name,
                'phone' => $driver->phone,
                'avatar' => $driver->avatar_url,
                'lat' => $driver->currentVehicle->last_latitude,
                'lng' => $driver->currentVehicle->last_longitude,
                'vehicle' => [
                    'id' => $driver->currentVehicle?->id,
                    'model' => $driver->currentVehicle?->model,
                    'plate' => $driver->currentVehicle?->plate,
                    'ignition_on' => $driver->currentVehicle?->ignition_on,
                    'current_speed' => $driver->currentVehicle?->current_speed,
                    'fuel_level' => $driver->currentVehicle?->fuel_level,
                ],
                'device' => [
                    'status' => $driver->currentVehicleDevice?->status,
                ],
                'trips' => $driver->trips->map(function ($trip) {
                    return [
                        'id' => $trip->id,
                        'started_at' => $trip->started_at,
                        'ended_at' => $trip->ended_at,
                        'distance_km' => $trip->distance_km,
                        'status' => $trip->status,
                    ];
                })->toArray(),
            ];
        });
    }
}
