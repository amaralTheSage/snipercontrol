<?php

use App\Http\Controllers\Api\TelemetryController;
use App\Http\Controllers\API\VideoController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\LivestreamController;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// Implementar middleware de autenticação de dispositivo
Route::group([], function () {
    Route::post('/telemetry', [TelemetryController::class, 'receiveTelemetry'])->name('telemetry.receive');
    Route::post('/telemetry/batch', [TelemetryController::class, 'receiveBatchTelemetry'])->name('telemetry.receiveBatch');

    Route::post('videos/upload', [VideoController::class, 'upload']);
    Route::post('/audios/upload', [AudioController::class, 'upload'])->name('audios.upload');

    // Streaming route
    Route::get('/audios/{recording}', [AudioController::class, 'show'])->name('audios.show');
});

// # middleware('auth:sanctum')->   # set this up eventualçly
// Route::post(
//     '/livekit/viewer-token',
//     [LivestreamController::class, 'viewerToken']
// );

// Route::post(
//     '/livekit/device-token',
//     [LivestreamController::class, 'deviceToken']
// );

/*
 * Generate a device token (device authenticates with device_token or device_id for dev)
 */
Route::post('/livekit/device-token', function (Request $request) {
    Log::info('Device video livestream route hit!');

    Log::info('Request TRANSMISSÃO: ', $request->toArray());


    $apiKey = config('livekit.key');
    $apiSecret = config('livekit.secret');
    $now = time();
    $deviceId = $request->input('device_id');

    if (! $deviceId) {
        return response()->json(['message' => 'device_id required'], 422);
    }

    // PROD: validate device with DB and device_token.
    // For quick test (dev), we skip DB check. DON'T DO THIS IN PRODUCTION.
    $room = 'device-' . $deviceId;

    $payload = [
        'iss' => $apiKey,
        'sub' => 'device-' . $deviceId,
        'identity' => 'device-' . $deviceId,
        'nbf' => $now,
        'exp' => $now + 3600,
        'video' => [
            'room' => $room,
            'roomJoin' => true,
            'canPublish' => true,
            'canSubscribe' => false,
        ],
    ];



    $jwt = JWT::encode($payload, $apiSecret, 'HS256');

    Log::info('ROOM TOKEN: ' . $jwt);

    return response()->json(['token' => $jwt, 'url' => config('livekit.url')]);
})->name('livekit.device-token');
