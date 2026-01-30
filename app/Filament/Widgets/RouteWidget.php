<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use App\Services\TripService;
use Filament\Widgets\Widget;

class RouteWidget extends Widget
{
    protected string $view = 'filament.widgets.route-widget';

    public ?int $vehicleId = null;
    public ?array $vehicleData = null;

    public function mount(?int $vehicleId = null): void
    {
        if (!$vehicleId) {
            return;
        }

        $this->vehicleId = $vehicleId;
        $this->vehicleData = $this->getVehicleData();
    }

    public function getVehicleData(): ?array
    {
        if (!$this->vehicleId) {
            return null;
        }

        $vehicle = Vehicle::with(['currentDriver', 'device'])
            ->find($this->vehicleId);

        if (!$vehicle) {
            return null;
        }

        $driver = $vehicle->currentDriver;
        $tripService = app(TripService::class);
        $currentTrip = $tripService->getCurrentTripForVehicle($vehicle->id);

        $tripData = null;
        if ($currentTrip) {
            $tripData = $tripService->formatTripForMap($currentTrip);
        }

        $currentLat = $vehicle->last_latitude ?? fake()->latitude(-23.7, -23.4);
        $currentLng = $vehicle->last_longitude ?? fake()->longitude(-46.8, -46.4);

        if ($tripData && !empty($tripData['current'])) {
            $currentLat = $tripData['current']['lat'];
            $currentLng = $tripData['current']['lng'];
        }

        return [
            'vehicle' => [
                'id' => $vehicle->id,
                'plate' => $vehicle->plate,
                'model' => $vehicle->model,
                'type' => $vehicle->type,
                'speed' => $vehicle->current_speed,
                'fuel_level' => $vehicle->fuel_level,
                'ignition_on' => $vehicle->ignition_on,
            ],

            'driver' => $driver ? [
                'id' => $driver->id,
                'name' => $driver->name,
                'phone' => $driver->phone,
                'avatar' => $driver->avatar_url,
            ] : null,

            'device' => $vehicle->device ? [
                'serial' => $vehicle->device->serial,
                'status' => $vehicle->device->status,
            ] : null,

            'trip' => $tripData,

            'lat' => $currentLat,
            'lng' => $currentLng,
        ];
    }
}
