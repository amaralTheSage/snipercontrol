<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Device extends Model
{
    use HasUuids;

    protected $fillable = [
        'mac_address',
        'vehicle_id',
        'status',
        'last_communication_at',
    ];

    protected $casts = [
        'last_communication_at' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function currentVehicleDriver()
    {
        return $this->hasOneThrough(
            Driver::class,   // Modelo final
            Vehicle::class,  // Modelo intermediário
            'id',            // PK em vehicles
            'id',            // PK em drivers
            'vehicle_id',    // FK em devices
            'current_driver_id' // FK em vehicles
        );
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id', 'id');
    }
}
