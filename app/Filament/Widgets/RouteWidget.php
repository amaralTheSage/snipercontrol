<?php

namespace App\Filament\Widgets;

use App\Models\Trip;
use App\Models\Vehicle;
use App\Services\TripService;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Log;

class RouteWidget extends Widget
{
    protected string $view = 'filament.widgets.route-widget';

    protected static bool $isDiscovered = false;

    public ?int $vehicleId = null;

    public ?array $vehicleData = null;

    public ?int $selectedTripId = null;

    public array $availableTrips = [];

    public bool $sidebarOpen = false;
    public bool $isLoading = true;

    public bool $viewingThroughWarning = false;
    public ?array $warningData = null;

    public function mount(?int $vehicleId = null, ?array $warningData = null): void
    {
        if (!$vehicleId) {
            $this->isLoading = false;
            return;
        }

        $this->vehicleId = $vehicleId;
        $this->loadTrips();

        // Select current trip by default
        $currentTrip = Trip::where('vehicle_id', $vehicleId)
            ->where('status', 'ongoing')
            ->latest('started_at')
            ->first();

        if ($currentTrip) {
            $this->selectedTripId = $currentTrip->id;
        } elseif (!empty($this->availableTrips)) {
            $this->selectedTripId = $this->availableTrips[0]['id'];
        }


        $this->vehicleData = $this->getVehicleData();
        $this->warningData = $warningData;
        $this->isLoading = false;
    }

    public function loadTrips(): void
    {
        $trips = Trip::where('vehicle_id', $this->vehicleId)
            ->orderBy('started_at', 'desc')
            ->limit(50) // Load last 50 trips
            ->get();

        $this->availableTrips = $trips->map(function ($trip) {
            return [
                'id' => $trip->id,
                'status' => $trip->status,
                'started_at' => $trip->started_at->format('d/m/Y H:i'),
                'ended_at' => $trip->ended_at?->format('d/m/Y H:i'),
                'distance_km' => number_format($trip->distance_km, 2),
                'duration_minutes' => $trip->duration ?? 0,
                'is_current' => $trip->status === 'ongoing',
            ];
        })->toArray();
    }

    public function selectTrip(int $tripId): void
    {
        $this->selectedTripId = $tripId;
        $this->vehicleData = $this->getVehicleData();

        // Dispatch event to update the map
        $this->dispatch('trip-selected', tripData: $this->vehicleData['trip'] ?? null);
    }

    public function toggleSidebar(): void
    {
        $this->sidebarOpen = ! $this->sidebarOpen;
    }

    public function refreshData()
    {

        Log::info('refreshData called');

        $this->loadTrips();
        $this->vehicleData = $this->getVehicleData();

        Log::info('Dispatching event', [
            'lat' => $this->vehicleData['lat'],
            'lng' => $this->vehicleData['lng'],
        ]);

        $this->dispatch(
            'vehicle-updated',
            vehicleData: $this->vehicleData,
        );
    }


    public function getVehicleData(): ?array
    {
        if (! $this->vehicleId) {
            return null;
        }

        $vehicle = Vehicle::with(['currentDriver', 'device'])
            ->find($this->vehicleId);

        if (! $vehicle) {
            return null;
        }

        $driver = $vehicle->currentDriver;
        $tripService = app(TripService::class);

        // Get the selected trip instead of current trip
        $selectedTrip = null;
        if ($this->selectedTripId) {
            $selectedTrip = Trip::find($this->selectedTripId);
        } else {
            $selectedTrip = $tripService->getCurrentTripForVehicle($vehicle->id);
        }

        $tripData = null;
        if ($selectedTrip) {
            $tripData = $tripService->formatTripForMap($selectedTrip);
        }

        $currentLat = $vehicle->last_latitude ?? fake()->latitude(-23.7, -23.4);
        $currentLng = $vehicle->last_longitude ?? fake()->longitude(-46.8, -46.4);

        if ($tripData && ! empty($tripData['current'])) {
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

            'warning' => $this->warningData
        ];
    }
}
