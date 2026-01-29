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
        // Simulate driver data
        $this->drivers = $this->getDriversProperty();
    }

    public function getDriversProperty()
    {
        return Driver::with([
            'currentVehicle.device',
        ])
            ->get()
            ->map(function ($driver) {
                return [
                    'name' => $driver->name,
                    'phone' => $driver->phone,
                    'avatar' => $driver->avatar_url,

                    'vehicle' => $driver->currentVehicle ? [
                        'plate' => $driver->currentVehicle->plate,
                        'model' => $driver->currentVehicle->model,
                    ] : null,

                    'device' => $driver->currentVehicle?->device ? [
                        'serial' => $driver->currentVehicle->device->serial,
                    ] : null,

                    // TEMP simulated coordinates
                    'lat' => fake()->latitude(-23.7, -23.4),  // São Paulo latitude range
                    'lng' => fake()->longitude(-46.8, -46.4),
                ];
            });
    }
}
