<?php

use App\Http\Controllers\API\VideoController;
use App\Http\Controllers\VideoThumbnailController;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
            'ffmpeg.binaries' => env('FFMPEG_BINARY', '/usr/bin/ffmpeg'),
            'ffprobe.binaries' => env('FFPROBE_BINARY', '/usr/bin/ffprobe'),
        ]);

        return 'FFMpeg is working! Version: ' . shell_exec('ffmpeg -version');
    } catch (\Exception $e) {
        return 'FFMpeg Error: ' . $e->getMessage();
    }
});

Route::get('/test-minio', function () {
    try {
        $disk = Storage::disk('minio');

        echo '<h2>MinIO Connection Test</h2>';

        // Test 1: Configuration check
        echo '<strong>Test 1: Configuration</strong><br>';
        echo 'Endpoint: ' . config('filesystems.disks.minio.endpoint') . '<br>';
        echo 'Bucket: ' . config('filesystems.disks.minio.bucket') . '<br>';
        echo 'Region: ' . config('filesystems.disks.minio.region') . '<br>';
        echo '✅ Config loaded<br><br>';

        // Test 2: Try to list files
        echo '<strong>Test 2: Listing files...</strong><br>';
        $files = $disk->allFiles();
        echo '✅ Connection successful!<br>';
        echo 'Found ' . count($files) . ' files<br><br>';

        // Show first 10 files
        if (count($files) > 0) {
            echo '<strong>First 10 files:</strong><br>';
            foreach (array_slice($files, 0, 10) as $file) {
                echo '📁 ' . $file . '<br>';
            }
            echo '<br>';
        }

        // Test 3: Check if first file exists and get basic info
        if (count($files) > 0) {
            $testFile = $files[0];
            echo '<strong>Test 3: File details</strong><br>';
            echo 'Testing with: ' . $testFile . '<br>';

            if ($disk->exists($testFile)) {
                echo '✅ File exists<br>';

                try {
                    $size = $disk->size($testFile);
                    echo 'Size: ' . number_format($size / 1024 / 1024, 2) . ' MB<br>';
                } catch (\Exception $e) {
                    echo '⚠️ Size check failed: ' . $e->getMessage() . '<br>';
                }

                echo '<br>';
            }
        }

        // Test 4: Try to get URL (direct URL, not temporary)
        if (count($files) > 0) {
            echo '<strong>Test 4: Get file URL</strong><br>';
            try {
                $url = $disk->url($files[0]);
                echo "✅ URL: <a href='{$url}' target='_blank'>{$url}</a><br>";
            } catch (\Exception $e) {
                echo '⚠️ URL generation failed: ' . $e->getMessage() . '<br>';
            }
            echo '<br>';
        }

        echo '<hr>';
        echo '<strong>✅ Basic connection test PASSED!</strong>';
    } catch (\Exception $e) {
        echo '<h2>❌ Connection Failed</h2>';
        echo '<strong>Error:</strong> ' . $e->getMessage() . '<br><br>';
        echo '<details><summary>Stack Trace</summary><pre>' . $e->getTraceAsString() . '</pre></details>';
    }
});

Route::get('/test-stream/{device}', function ($device) {
    return view('test-publish', ['device' => $device]);
});

Route::get('/test-mqtt', function () {
    try {
        $mqtt = new \PhpMqtt\Client\MqttClient(
            '15.229.157.215',
            1883,
            'laravel-test-' . uniqid()
        );

        $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'))
            ->setKeepAliveInterval(60);

        Log::info('Connecting to MQTT broker...');
        $mqtt->connect($connectionSettings, true);

        Log::info('Publishing to vehicle/cmd...');
        $mqtt->publish('vehicle/cmd', 'CUTOFF', 0);

        Log::info('Disconnecting...');
        $mqtt->disconnect();

        Log::info('✅ MQTT test successful');
        return '✅ MQTT command sent! Check Raspberry Pi logs.';
    } catch (\Exception $e) {
        Log::error('❌ MQTT test failed: ' . $e->getMessage());
        return '❌ Error: ' . $e->getMessage();
    }
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
        'url' => config('livekit.url'),
    ]);
});

require __DIR__ . '/settings.php';
