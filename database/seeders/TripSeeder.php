<?php

namespace Database\Seeders;

use App\Models\Trip;
use App\Models\TelemetryEvent;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        Vehicle::with('device')->get()->each(function (Vehicle $vehicle) {

            if (!$vehicle->device) {
                return;
            }

            // ---- FINISHED TRIP ----
            $finishedTrip = Trip::create([
                'vehicle_id'  => $vehicle->id,
                'driver_id'   => $vehicle->current_driver_id,
                'device_id'   => $vehicle->device->id,
                'started_at'  => now()->subDays(2),
                'ended_at'    => now()->subDays(2)->addMinutes(45),
                'start_lat'   => -23.6400000,
                'start_lng'   => -46.6900000,
                'end_lat'     => -23.6500000,
                'end_lng'     => -46.7000000,
                'distance_km' => 18.2,
                'status'      => 'finished',
            ]);

            $this->seedTelemetry($finishedTrip, 15, false);

            // ---- ONGOING TRIP (IMPORTANT) ----
            $ongoingTrip = Trip::create([
                'vehicle_id'  => $vehicle->id,
                'driver_id'   => $vehicle->current_driver_id,
                'device_id'   => $vehicle->device->id,
                'started_at'  => now()->subMinutes(30),
                'ended_at'    => null,
                'start_lat'   => -23.6411210,
                'start_lng'   => -46.6897700,
                'end_lat'     => null,
                'end_lng'     => null,
                'distance_km' => 0,
                'status'      => 'ongoing',
            ]);

            $this->seedTelemetry($ongoingTrip, 20, true);
        });
    }

    private function seedTelemetry(Trip $trip, int $points, bool $ongoing): void
    {
        $lat = (float) $trip->start_lat;
        $lng = (float) $trip->start_lng;

        $startTime = $trip->started_at;
        $interval  = 2; // minutes between points

        for ($i = 0; $i < $points; $i++) {
            $lat += rand(-5, 5) * 0.0001;
            $lng += rand(-5, 5) * 0.0001;

            TelemetryEvent::create([
                'trip_id'     => $trip->id,
                'recorded_at' => $startTime->copy()->addMinutes($i * $interval),

                'lat' => round($lat, 7),
                'lng' => round($lng, 7),

                'speed'       => $ongoing ? rand(30, 90) : rand(20, 80),
                'fuel'        => max(10, 100 - ($i * 2)),
                'ignition_on' => $ongoing,
            ]);
        }

        // Final stop point for finished trips
        if (!$ongoing && $trip->ended_at) {
            TelemetryEvent::create([
                'trip_id'     => $trip->id,
                'recorded_at' => $trip->ended_at,
                'lat'         => $trip->end_lat,
                'lng'         => $trip->end_lng,
                'speed'       => 0,
                'fuel'        => max(10, 100 - ($points * 2)),
                'ignition_on' => false,
            ]);
        }
    }
}
