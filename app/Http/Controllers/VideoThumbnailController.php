<?php

namespace App\Http\Controllers;

use App\Models\VideoRecording;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VideoThumbnailController extends Controller
{
    public function show(VideoRecording $recording)
    {
        $thumbnailPath = $recording->getThumbnailPath();
        $disk = $recording->storage_disk;

        // If thumbnail already exists, serve it
        if (Storage::disk($disk)->exists($thumbnailPath)) {
            return response()->file(
                Storage::disk($disk)->path($thumbnailPath),
                ['Content-Type' => 'image/jpeg']
            );
        }

        // Generate thumbnail on-demand
        try {
            $this->generateThumbnail($recording);

            if (Storage::disk($disk)->exists($thumbnailPath)) {
                return response()->file(
                    Storage::disk($disk)->path($thumbnailPath),
                    ['Content-Type' => 'image/jpeg']
                );
            }
        } catch (\Exception $e) {
            Log::error('Thumbnail generation failed', [
                'recording_id' => $recording->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function generateThumbnail(VideoRecording $recording): void
    {
        $videoPath = Storage::disk($recording->storage_disk)->path($recording->storage_path);
        $thumbnailPath = Storage::disk($recording->storage_disk)->path($recording->getThumbnailPath());

        $ffmpegBinary = env('FFMPEG_BINARY', 'ffmpeg');
        $ffprobeBinary = env('FFPROBE_BINARY', 'ffprobe');

        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries' => $ffmpegBinary,
            'ffprobe.binaries' => $ffprobeBinary,
            'timeout' => 60,
            'ffmpeg.threads' => 4,
        ]);

        $video = $ffmpeg->open($videoPath);

        // Extract frame at 2 seconds or middle of video
        $time = $recording->duration ? min(2, floor($recording->duration / 2)) : 2;

        $frame = $video->frame(TimeCode::fromSeconds($time));
        $frame->save($thumbnailPath);
    }
}
