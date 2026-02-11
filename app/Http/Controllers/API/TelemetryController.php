<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\TelemetryEvent;
use App\Models\Trip;
use App\Services\WarningDetectionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TelemetryController extends Controller
{
    protected WarningDetectionService $warningService;

    public function __construct(WarningDetectionService $warningService)
    {
        $this->warningService = $warningService;
    }

    /**
     * Receive telemetry data from hardware device
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function receiveTelemetry(Request $request, string $mac)
    {
        Log::info('%--%--%--% Received telemetry data ', $request->toArray());

        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'speed' => 'nullable|numeric|min:0',
            'fuel' => 'nullable|numeric|min:0|max:100',
            'ignition_on' => 'required|boolean',
            'recorded_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $device = Device::where('mac_address', $mac)->first();
        Log::info('%--%--%--% Mac: ' . $mac . ' exists: ' . ($device ? 'yes' : 'no'));


        if (! $device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found',
            ], 404);
        }

        $trip = $this->handleTrip($device, $request);
        if (! $trip) {
            return response()->json(['success' => true, 'message' => 'Ignition off'], 200);
        }

        $event = TelemetryEvent::create([
            'trip_id' => $trip->id,
            'recorded_at' => $request->recorded_at ? Carbon::parse($request->recorded_at) : now(),
            'lat' => $request->lat,
            'lng' => $request->lng,
            'speed' => $request->speed ?? 0,
            'fuel' => $request->fuel,
            'ignition_on' => $request->ignition_on,
        ]);

        $this->updateTrip($trip, $request);

        $device->vehicle->update([
            'last_latitude' => $request->lat,
            'last_longitude' => $request->lng,
            'current_speed' => $request->speed ?? 0,
            'fuel_level' => $request->fuel,
            'ignition_on' => $request->ignition_on,
            'last_update_at' => now(),
        ]);

        $this->warningService->checkForSuspiciousActivity($device, $event, $trip);
        $this->warningService->checkUnexpectedStop($device, $event);

        return response()->json([
            'success' => true,
            'trip_id' => $trip->id,
            'telemetry_event_id' => $event->id,
        ], 201);
    }


    /**
     * Handle trip creation or retrieval based on ignition status
     */
    private function handleTrip(Device $device, Request $request): ?Trip
    {
        // Get the current ongoing trip for this device
        $currentTrip = Trip::where('device_id', $device->id)
            ->where('status', 'ongoing')
            ->latest('started_at')
            ->first();

        // If ignition is on
        if ($request->ignition_on) {
            // If there's no ongoing trip, create a new one
            if (! $currentTrip) {
                $currentTrip = Trip::create([
                    'vehicle_id' => $device->vehicle_id,
                    'driver_id' => $device->driver_id ?? null,
                    'device_id' => $device->id,
                    'started_at' => $request->recorded_at ? Carbon::parse($request->recorded_at) : now(),
                    'start_lat' => $request->lat,
                    'start_lng' => $request->lng,
                    'status' => 'ongoing',
                    'distance_km' => 0,
                ]);

                Log::info('New trip started', [
                    'trip_id' => $currentTrip->id,
                    'device_id' => $device->id,
                ]);
            }

            return $currentTrip;
        }

        // If ignition is off and there's an ongoing trip, end it
        if (! $request->ignition_on && $currentTrip) {
            $currentTrip->update([
                'ended_at' => $request->recorded_at ? Carbon::parse($request->recorded_at) : now(),
                'end_lat' => $request->lat,
                'end_lng' => $request->lng,
                'status' => 'finished',
            ]);

            Log::info('Trip ended', [
                'trip_id' => $currentTrip->id,
                'device_id' => $device->id,
            ]);
        }

        return $currentTrip;
    }

    /**
     * Update trip information with latest telemetry
     */
    private function updateTrip(Trip $trip, Request $request): void
    {
        // Update end location
        $trip->end_lat = $request->lat;
        $trip->end_lng = $request->lng;

        // Calculate total distance using Haversine formula
        $distance = $this->calculateTotalDistance($trip);
        $trip->distance_km = $distance;

        $trip->save();
    }

    /**
     * Calculate total distance traveled in a trip
     */
    private function calculateTotalDistance(Trip $trip): float
    {
        $events = $trip->telemetryEvents()
            ->orderBy('recorded_at')
            ->get(['lat', 'lng']);

        if ($events->count() < 2) {
            return 0;
        }

        $totalDistance = 0;

        for ($i = 0; $i < $events->count() - 1; $i++) {
            $point1 = $events[$i];
            $point2 = $events[$i + 1];

            $totalDistance += $this->haversineDistance(
                $point1->lat,
                $point1->lng,
                $point2->lat,
                $point2->lng
            );
        }

        return round($totalDistance, 2);
    }

    /**
     * Calculate distance between two GPS coordinates using Haversine formula
     *
     * @return float Distance in kilometers
     */
    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lngDelta / 2) * sin($lngDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Batch receive multiple telemetry events
     * Useful for devices that store data offline and send in batches
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function receiveBatchTelemetry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required',
            'events' => 'required|array|min:1',
            'events.*.lat' => 'required|numeric|between:-90,90',
            'events.*.lng' => 'required|numeric|between:-180,180',
            'events.*.speed' => 'nullable|numeric|min:0',
            'events.*.fuel' => 'nullable|numeric|min:0|max:100',
            'events.*.ignition_on' => 'required|boolean',
            'events.*.recorded_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $successCount = 0;
            $failedCount = 0;
            $errors = [];

            // Sort events by recorded_at to process in chronological order
            $events = collect($request->events)->sortBy('recorded_at');

            foreach ($events as $index => $eventData) {
                try {
                    $eventRequest = new Request([
                        'device_id' => $request->device_id,
                        'lat' => $eventData['lat'],
                        'lng' => $eventData['lng'],
                        'speed' => $eventData['speed'] ?? null,
                        'fuel' => $eventData['fuel'] ?? null,
                        'ignition_on' => $eventData['ignition_on'],
                        'recorded_at' => $eventData['recorded_at'],
                    ]);

                    $response = $this->receiveTelemetry($eventRequest);

                    if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                        $successCount++;
                    } else {
                        $failedCount++;
                        $errors[] = "Event {$index}: " . json_decode($response->getContent())->message;
                    }
                } catch (\Exception $e) {
                    $failedCount++;
                    $errors[] = "Event {$index}: " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Processed {$successCount} events successfully",
                'data' => [
                    'total_events' => count($request->events),
                    'successful' => $successCount,
                    'failed' => $failedCount,
                    'errors' => $errors,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Batch telemetry receiving error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing batch telemetry data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }
}
