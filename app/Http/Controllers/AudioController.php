<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAudioRecording;
use App\Models\AudioRecording;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AudioController extends Controller
{
    /**
     * Upload audio file from device
     */
    public function upload(Request $request)
    {
        Log::info(('Upload Áudio: request chegou, device: '.$request->device_id));
        $rules = [
            'device_id' => 'required|exists:devices,id',
            'audio' => 'required|file|mimetypes:audio/mpeg,audio/wav,audio/aac,audio/mp4,video/mp4,audio/x-m4a,audio/ogg,audio/webm',
            'trip_id' => 'nullable|exists:trips,id',
            'start_lat' => 'nullable|numeric',
            'start_lng' => 'nullable|numeric',
            'warning_id' => 'nullable|exists:warnings,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::error('Erro na validação do áudio:', $validator->errors()->toArray());

            return response()->json(['errors' => $validator->errors()], 422);
        }
        Log::info('Upload Áudio: passou da validação');

        try {
            $device = Device::findOrFail($request->device_id);
            $file = $request->file('audio');

            // Generate unique filename
            $filename = \sprintf(
                'audio_%d_%s_%s.%s',
                $device->vehicle_id,
                now()->format('YmdHis'),
                Str::random(8),
                $file->getClientOriginalExtension()
            );

            Log::info('Upload Áudio: gerou filename');

            // Store file
            $path = $file->storeAs(
                "audios/{$device->vehicle_id}/".now()->format('Y/m'),
                $filename,
                'local'
            );

            Log::info('Upload Áudio: guardou arquivo');

            // Get the vehicle's latest ongoing trip
            $trip = $device->vehicle->trips()
                ->where('status', 'ongoing')
                ->latest('started_at')
                ->first();

            // Create recording record
            $recording = AudioRecording::create([
                'vehicle_id' => $device->vehicle_id,
                'driver_id' => $device->vehicle->currentDriver?->id,
                'device_id' => $device->id,
                'trip_id' => $trip?->id,
                'filename' => $filename,
                'storage_path' => $path,
                'storage_disk' => 'local',
                'file_size' => $file->getSize(),
                'warning_id' => $request->warning_id,
                'start_latitude' => $request->start_lat,
                'start_longitude' => $request->start_lng,
                'status' => 'pending', // Explicitly set pending
            ]);
            Log::info('Upload Áudio: criou audio');

            // Queue audio processing job (extract metadata)
            ProcessAudioRecording::dispatch($recording);
            Log::info('Upload Áudio: processou audio ');

            return response()->json([
                'success' => true,
                'message' => 'Audio uploaded successfully',
                'data' => [
                    'recording_id' => $recording->id,
                    'status' => $recording->status,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    public function show(AudioRecording $recording)
    {
        // TODO: Add tenant authorization here

        if (! Storage::disk($recording->storage_disk)->exists($recording->storage_path)) {
            abort(404);
        }

        $path = Storage::disk($recording->storage_disk)
            ->path($recording->storage_path);

        return response()->file($path, [
            'Content-Type' => mime_content_type($path),
            'Accept-Ranges' => 'bytes',
        ]);
    }
}
