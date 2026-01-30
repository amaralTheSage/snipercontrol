<?php

namespace App\Services;

use App\Models\Trip;

class TripService
{
    public function getCurrentTripForVehicle(int $vehicleId): ?Trip
    {
        return Trip::with(['vehicle', 'driver', 'device', 'telemetryEvents'])
            ->where('vehicle_id', $vehicleId)
            ->where('status', 'ongoing')
            ->latest('started_at')
            ->first();
    }

    public function getTripRoute(Trip $trip): array
    {
        $telemetryEvents = $trip->telemetryEvents()
            ->orderBy('recorded_at')
            ->get();

        if ($telemetryEvents->isEmpty()) {
            return [];
        }

        return $telemetryEvents->map(function ($event) {
            return [
                'lat' => (float) $event->lat,
                'lng' => (float) $event->lng,
                'speed' => $event->speed,
                'fuel' => $event->fuel,
                'recorded_at' => $event->recorded_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();
    }

    public function getTripStats(Trip $trip): array
    {
        $telemetryEvents = $trip->telemetryEvents()
            ->orderBy('recorded_at')
            ->get();

        if ($telemetryEvents->isEmpty()) {
            return [
                'max_speed' => 0,
                'avg_speed' => 0,
                'duration_minutes' => 0,
                'total_points' => 0,
            ];
        }

        $speeds = $telemetryEvents->pluck('speed')->filter();

        return [
            'max_speed' => $speeds->max() ?? 0,
            'avg_speed' => $speeds->avg() ?? 0,
            'duration_minutes' => $trip->duration ?? 0,
            'total_points' => $telemetryEvents->count(),
            'distance_km' => $trip->distance_km,
        ];
    }

    public function formatTripForMap(Trip $trip): array
    {
        $route = $this->getTripRoute($trip);
        $stats = $this->getTripStats($trip);

        $currentPosition = end($route) ?: [
            'lat' => (float) $trip->start_lat,
            'lng' => (float) $trip->start_lng,
        ];

        return [
            'id' => $trip->id,
            'status' => $trip->status,
            'started_at' => $trip->started_at->format('d/m/Y H:i'),
            'ended_at' => $trip->ended_at?->format('d/m/Y H:i'),
            'start' => [
                'lat' => (float) $trip->start_lat,
                'lng' => (float) $trip->start_lng,
            ],
            'current' => $currentPosition,
            'route' => $route,
            'stats' => $stats,
        ];
    }
}
