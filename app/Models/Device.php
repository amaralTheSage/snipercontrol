<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'serial',
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
}
