<?php

use App\Http\Controllers\API\VideoController;
use App\Http\Controllers\VideoThumbnailController;
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



Route::get('/test-publish/{device}', function ($device) {
    return view('test-publish', ['device' => $device]);
});


require __DIR__ . '/settings.php';
