<?php

namespace App\Jobs;

use App\Models\VideoRecording;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;

class ProcessVideoRecording implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public VideoRecording $recording
    ) {}

    public function handle(): void
    {
        try {
            $this->recording->update(['status' => 'processing']);

            $videoPath = Storage::disk($this->recording->storage_disk)->path($this->recording->storage_path);

            // Initialize FFMpeg
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => config('services.ffmpeg.binary'),
                'ffprobe.binaries' => config('services.ffmpeg.probe'),
                'timeout'          => 3600,
                'ffmpeg.threads'   => 12,
            ]);

            $video = $ffmpeg->open($videoPath);

            // Get video duration
            $duration = (int) $video->getStreams()->videos()->first()->get('duration');

            // Generate thumbnail at 2 seconds (or middle of video if shorter)
            $thumbnailTime = min(2, floor($duration / 2));
            $thumbnailFullPath = Storage::disk($this->recording->storage_disk)->path(
                $this->recording->getThumbnailPath()
            );

            $frame = $video->frame(TimeCode::fromSeconds($thumbnailTime));
            $frame->save($thumbnailFullPath);

            // Update recording with metadata
            $this->recording->update([
                'duration' => $duration,
                'status' => 'ready',
                'processing_error' => null,
            ]);
        } catch (\Exception $e) {
            $this->recording->update([
                'status' => 'failed',
                'processing_error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
