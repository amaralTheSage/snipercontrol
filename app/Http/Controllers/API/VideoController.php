<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessVideoRecording;
use App\Models\Device;
use App\Models\VideoRecording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    /**
     * Upload video file from device
     */
    public function upload(Request $request)
    {
        $rules = [
            'device_id' => 'required|exists:devices,id',
            'video' => 'required|file|mimes:mp4,avi,mov,mkv|max:512000',
            'trip_id' => 'nullable|exists:trips,id',
            'start_lat' => 'nullable|numeric|between:-90,90',
            'start_lng' => 'nullable|numeric|between:-180,180',
            'warning_id' => 'nullable|exists:warnings,id',
        ];

        $request->validate($rules);

        try {
            $device = Device::findOrFail($request->device_id);
            $file = $request->file('video');


            // Generate unique filename
            $filename = \sprintf(
                'vehicle_%d_%s_%s.%s',
                $device->vehicle_id,
                now()->format('YmdHis'),
                Str::random(8),
                $file->getClientOriginalExtension()
            );

            // Store file
            $path = $file->storeAs(
                "videos/{$device->vehicle_id}/" . now()->format('Y/m'),
                $filename,
                'local',

            );

            // Get the vehicle's latest ongoing trip
            $trip = $device->vehicle->trips()
                ->where('status', 'ongoing')
                ->latest('started_at')
                ->first();

            // Create recording record
            $recording = VideoRecording::create([
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
            ]);

            // TODO: Queue video processing job (extract metadata, generate thumbnail, etc.)
            ProcessVideoRecording::dispatch($recording);

            return response()->json([
                'success' => true,
                'message' => 'Video uploaded successfully',
                'data' => [
                    'recording_id' => $recording->id,
                    'status' => $recording->status,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function show(VideoRecording $recording)
    {
        // TODO: add proper company/tenant authorization here
        // example:
        // abort_unless(auth()->user()->company_id === $recording->vehicle->company_id, 403);

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




    /**
     * Upload video in chunks (for large files)
     */
    public function uploadChunk(Request $request)
    {
        // TODO: Implement chunked upload logic
        // This allows devices to upload large videos in smaller pieces
    }

    /**
     * Mark upload as complete and trigger processing
     */
    public function completeUpload(Request $request)
    {
        // TODO: Finalize chunked upload and start processing
    }
}
