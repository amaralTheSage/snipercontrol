<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'device_id',
        'started_at',
        'ended_at',
        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
        'distance_km',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

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

    public function videoRecordings(): HasMany
    {
        return $this->hasMany(VideoRecording::class);
    }
    public function telemetryEvents(): HasMany
    {
        return $this->hasMany(TelemetryEvent::class);
    }

    public function latestEvent()
    {
        return $this->telemetryEvents()->latest('recorded_at');
    }
}
