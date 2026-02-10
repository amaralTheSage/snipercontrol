<?php

namespace App\Jobs;

use App\Models\AudioRecording;
use FFMpeg\FFMpeg;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessAudioRecording implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public AudioRecording $recording
    ) {}

    public function handle(): void
    {
        try {
            $this->recording->update(['status' => 'processing']);

            $audioPath = Storage::disk($this->recording->storage_disk)->path($this->recording->storage_path);

            // Initialize FFMpeg
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries' => config('services.ffmpeg.binary'),
                'ffprobe.binaries' => config('services.ffmpeg.probe'),
                'timeout' => 3600,
                'ffmpeg.threads' => 12,
            ]);

            // Open the audio file
            $audio = $ffmpeg->open($audioPath);

            // Get audio duration
            // Note: getStreams()->audios() is used instead of videos()
            $duration = (int) $audio->getStreams()->audios()->first()->get('duration');

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

            // Don't re-throw in production if you want to avoid retry loops for corrupt files,
            // but throwing allows the queue worker to handle retries.
            throw $e;
        }
    }
}
