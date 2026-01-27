<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name',
        'cpf',
        'phone',
        'status',
        'current_vehicle_id',
    ];

    public function currentVehicle()
    {
        return $this->hasOne(Vehicle::class, 'current_vehicle_id');
    }
}
