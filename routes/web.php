<?php

use App\Http\Controllers\API\VideoController;
use App\Http\Controllers\VideoThumbnailController;
use App\Models\Device;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::get('/videos/{recording}/thumbnail', [VideoThumbnailController::class, 'show'])
    ->name('video.thumbnail');


// test routes
Route::get('/test-upload', function () {
    return view('test-upload');
});

Route::get('/videos/{recording}', [VideoController::class, 'show'])
    ->name('videos.show');

Route::get('/test-ffmpeg', function () {
    try {
        $ffmpeg = FFMpeg\FFMpeg::create([
            'ffmpeg.binaries'  => env('FFMPEG_BINARY', '/usr/bin/ffmpeg'),
            'ffprobe.binaries' => env('FFPROBE_BINARY', '/usr/bin/ffprobe'),
        ]);

        return 'FFMpeg is working! Version: ' . shell_exec('ffmpeg -version');
    } catch (\Exception $e) {
        return 'FFMpeg Error: ' . $e->getMessage();
    }
});



Route::get('/test-stream/{device}', function ($device) {
    return view('test-publish', ['device' => $device]);
});

Route::post('/livekit/viewer-token', function (Request $request) {
    $request->validate([
        'device_id' => 'required',
    ]);

    $device = Device::where('id', $request->device_id)->firstOrFail();

    if ($device->company_id !== Auth::id()) {
        abort(403);
    }

    $apiKey = config('livekit.key');
    $apiSecret = config('livekit.secret');
    $now = time();
    $roomName = 'device-' . $request->device_id;

    $payload = [
        'iss' => $apiKey,
        'sub' => 'viewer-' . Auth::id() . '-' . uniqid(),
        'iat' => $now,
        'nbf' => $now,
        'exp' => $now + 3600,
        'video' => [
            'room' => $roomName,
            'roomJoin' => true,
            'canPublish' => false,
            'canPublishData' => false,
            'canSubscribe' => true,
        ],
    ];

    $jwt = Firebase\JWT\JWT::encode($payload, $apiSecret, 'HS256');

    return response()->json([
        'token' => $jwt,
        'url' => config('livekit.url')
    ]);
});



require __DIR__ . '/settings.php';
