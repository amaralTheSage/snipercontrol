<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'plate',
        'model',
        'year',
        'type',
        'status',
        'current_driver_id',
        'current_speed',
        'fuel_level',
        'ignition_on',
        'last_latitude',
        'last_longitude',
        'last_update_at',
    ];

    protected $casts = [
        'ignition_on' => 'boolean',
        'last_update_at' => 'datetime',
    ];

    public function device()
    {
        return $this->hasOne(Device::class);
    }

    public function videoRecordings(): HasMany
    {
        return $this->hasMany(VideoRecording::class);
    }

    public function audioRecordings(): HasMany
    {
        return $this->hasMany(AudioRecording::class);
    }

    public function currentDriver()
    {
        return $this->belongsTo(Driver::class, 'current_driver_id');
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function warnings(): HasMany
    {
        return $this->hasMany(Warning::class);
    }

    public function driverHistory(): HasMany
    {
        return $this->hasMany(DriverHistory::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id', 'id');
    }
}
