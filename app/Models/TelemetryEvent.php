<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelemetryEvent extends Model
{
    protected $fillable = [
        'trip_id',
        'recorded_at',
        'lat',
        'lng',
        'speed',
        'fuel',
        'ignition_on',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'ignition_on' => 'boolean',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
