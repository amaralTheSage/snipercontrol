<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'relay_enabled',
        'last_latitude',
        'last_longitude',
        'last_update_at',
    ];

    protected $casts = [
        'ignition_on' => 'boolean',
        'relay_enabled' => 'boolean',
        'last_update_at' => 'datetime',
    ];

    public function device()
    {
        return $this->hasOne(Device::class);
    }

    public function currentDriver()
    {
        return $this->belongsTo(Driver::class, 'current_driver_id');
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
