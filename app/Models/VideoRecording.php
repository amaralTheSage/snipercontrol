<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class VideoRecording extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'device_id',
        'trip_id',
        'warning_id',
        'filename',
        'storage_path',
        'storage_disk',
        'file_size',
        'duration',
        'start_latitude',
        'start_longitude',
        'end_latitude',
        'end_longitude',
        'status',
        'processing_error',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'duration' => 'integer',
    ];

    // Relationships
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function warning(): BelongsTo
    {
        return $this->belongsTo(Warning::class);
    }

    // Scopes
    public function scopeByVehicle($query, int $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    // Helpers
    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    public function getDurationHumanAttribute(): string
    {
        if (!$this->duration) {
            return 'Unknown';
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getVideoUrl(): string
    {
        return Storage::disk($this->storage_disk)->url($this->storage_path);
    }

    public function getThumbnailPath(): string
    {
        // Derive thumbnail path from video path
        $pathInfo = pathinfo($this->storage_path);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.jpg';
    }

    public function getThumbnailUrl(): string
    {
        // Return route that generates thumbnail on-demand
        return route('video.thumbnail', ['recording' => $this->id]);
    }

    public function thumbnailExists(): bool
    {
        return Storage::disk($this->storage_disk)->exists($this->getThumbnailPath());
    }

    public function deleteFile(): bool
    {
        $deleted = true;

        if ($this->storage_path && Storage::disk($this->storage_disk)->exists($this->storage_path)) {
            $deleted = Storage::disk($this->storage_disk)->delete($this->storage_path);
        }

        $thumbnailPath = $this->getThumbnailPath();
        if (Storage::disk($this->storage_disk)->exists($thumbnailPath)) {
            Storage::disk($this->storage_disk)->delete($thumbnailPath);
        }

        return $deleted;
    }

    public function isReady(): bool
    {
        return $this->status === 'ready';
    }
}
