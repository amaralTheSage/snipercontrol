<?php

use App\Models\Device;
use App\Models\Trip;
use App\Models\Vehicle;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

beforeEach(function () {
    // Create a test vehicle with correct fillable fields
    $this->vehicle = Vehicle::create([
        'plate' => 'ABC-1234',
        'model' => 'Toyota Corolla',
        'year' => 2024,
        'type' => 'car',
        'status' => 'active',
    ]);

    // Create a test device with correct fillable fields
    $this->device = Device::create([
        'mac_address' => 'TEST_DEVICE_001',
        'vehicle_id' => $this->vehicle->id,
        'status' => 'online',
    ]);
});

test('it can receive telemetry and start a new trip', function () {
    $response = postJson('/api/telemetry', [
        'device_id' => $this->device->id,
        'lat' => -30.0346,
        'lng' => -51.2177,
        'speed' => 60.5,
        'fuel' => 80.0,
        'ignition_on' => true,
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Telemetry data received successfully',
        ]);

    // Assert trip was created
    expect(Trip::where('device_id', $this->device->id)->exists())->toBeTrue();

    assertDatabaseHas('trips', [
        'device_id' => $this->device->id,
        'status' => 'ongoing',
        'start_lat' => -30.0346,
        'start_lng' => -51.2177,
    ]);

    // Assert telemetry event was created
    assertDatabaseHas('telemetry_events', [
        'lat' => -30.0346,
        'lng' => -51.2177,
        'speed' => 60.5,
        'fuel' => 80.0,
        'ignition_on' => true,
    ]);
});

test('it can add telemetry to existing trip', function () {
    // Create an ongoing trip with explicit data
    $trip = Trip::create([
        'device_id' => $this->device->id,
        'vehicle_id' => $this->vehicle->id,
        'status' => 'ongoing',
        'start_lat' => -30.0346,
        'start_lng' => -51.2177,
        'started_at' => now(),
        'distance_km' => 0,
    ]);

    $response = postJson('/api/telemetry', [
        'device_id' => $this->device->id,
        'lat' => -30.0356,
        'lng' => -51.2187,
        'speed' => 65.0,
        'fuel' => 79.5,
        'ignition_on' => true,
    ]);

    $response->assertStatus(201);

    // Assert new telemetry event was added to the same trip
    expect($trip->telemetryEvents()->count())->toBe(1);
});

test('it can end a trip when ignition turns off', function () {
    // Create an ongoing trip with explicit data
    $trip = Trip::create([
        'device_id' => $this->device->id,
        'vehicle_id' => $this->vehicle->id,
        'status' => 'ongoing',
        'start_lat' => -30.0346,
        'start_lng' => -51.2177,
        'started_at' => now(),
        'distance_km' => 0,
    ]);

    $response = postJson('/api/telemetry', [
        'device_id' => $this->device->id,
        'lat' => -30.0366,
        'lng' => -51.2197,
        'speed' => 0,
        'fuel' => 78.0,
        'ignition_on' => false,
    ]);

    $response->assertStatus(201);

    // Assert trip was completed
    $trip->refresh();
    expect($trip->status)->toBe('completed');
    expect($trip->ended_at)->not->toBeNull();
    expect($trip->end_lat)->toBe(-30.0366);
    expect($trip->end_lng)->toBe(-51.2197);
});

test('it validates required fields', function () {
    $response = postJson('/api/telemetry', [
        'device_id' => $this->device->id,
        // Missing required fields
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['lat', 'lng', 'ignition_on']);
});

test('it validates latitude range', function () {
    $response = postJson('/api/telemetry', [
        'device_id' => $this->device->id,
        'lat' => 95.0, // Invalid: > 90
        'lng' => -51.2177,
        'ignition_on' => true,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['lat']);
});

test('it validates longitude range', function () {
    $response = postJson('/api/telemetry', [
        'device_id' => $this->device->id,
        'lat' => -30.0346,
        'lng' => 190.0, // Invalid: > 180
        'ignition_on' => true,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['lng']);
});

test('it returns 404 for non-existent device', function () {
    $response = postJson('/api/telemetry', [
        'device_id' => 'NON_EXISTENT_DEVICE',
        'lat' => -30.0346,
        'lng' => -51.2177,
        'ignition_on' => true,
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Device not found',
        ]);
});

test('it can receive batch telemetry', function () {
    $response = postJson('/api/telemetry/batch', [
        'device_id' => $this->device->id,
        'events' => [
            [
                'lat' => -30.0346,
                'lng' => -51.2177,
                'speed' => 60.0,
                'fuel' => 80.0,
                'ignition_on' => true,
                'recorded_at' => '2024-01-30 14:00:00',
            ],
            [
                'lat' => -30.0356,
                'lng' => -51.2187,
                'speed' => 65.0,
                'fuel' => 79.5,
                'ignition_on' => true,
                'recorded_at' => '2024-01-30 14:01:00',
            ],
            [
                'lat' => -30.0366,
                'lng' => -51.2197,
                'speed' => 70.0,
                'fuel' => 79.0,
                'ignition_on' => true,
                'recorded_at' => '2024-01-30 14:02:00',
            ],
        ],
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'total_events' => 3,
                'successful' => 3,
                'failed' => 0,
            ],
        ]);

    // Assert all telemetry events were created
    expect($this->device->trips()->first()->telemetryEvents()->count())->toBe(3);
});

test('it validates batch telemetry events array', function () {
    $response = postJson('/api/telemetry/batch', [
        'device_id' => $this->device->id,
        'events' => [], // Empty array
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['events']);
});

test('it calculates trip distance correctly', function () {
    // Start a trip
    postJson('/api/telemetry', [
        'device_id' => $this->device->id,
        'lat' => -30.0346,
        'lng' => -51.2177,
        'speed' => 60.0,
        'fuel' => 80.0,
        'ignition_on' => true,
    ]);

    // Add another point (approximately 1.5 km away)
    postJson('/api/telemetry', [
        'device_id' => $this->device->id,
        'lat' => -30.0456,
        'lng' => -51.2277,
        'speed' => 65.0,
        'fuel' => 79.5,
        'ignition_on' => true,
    ]);

    $trip = Trip::where('device_id', $this->device->id)
        ->where('status', 'ongoing')
        ->first();

    // Distance should be calculated (approximately 1.5 km)
    expect($trip->distance_km)->toBeGreaterThan(0);
    expect($trip->distance_km)->toBeLessThan(2); // Should be less than 2km
});

test('it does not create telemetry event when ignition is off and no active trip exists', function () {
    $response = postJson('/api/telemetry', [
        'device_id' => $this->device->id,
        'lat' => -30.0346,
        'lng' => -51.2177,
        'speed' => 0,
        'fuel' => 80.0,
        'ignition_on' => false,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => false,
            'message' => 'No active trip and ignition is off',
        ]);

    // Assert no trip was created
    expect(Trip::where('device_id', $this->device->id)->exists())->toBeFalse();
});

test('it accepts optional recorded_at timestamp', function () {
    $customTimestamp = '2024-01-15 10:30:00';

    $response = postJson('/api/telemetry', [
        'device_id' => $this->device->id,
        'lat' => -30.0346,
        'lng' => -51.2177,
        'speed' => 60.0,
        'fuel' => 80.0,
        'ignition_on' => true,
        'recorded_at' => $customTimestamp,
    ]);

    $response->assertStatus(201);

    // Assert telemetry event has the custom timestamp
    assertDatabaseHas('telemetry_events', [
        'lat' => -30.0346,
        'lng' => -51.2177,
    ]);
});
