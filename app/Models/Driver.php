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
    ];

    public function currentVehicle()
    {
        return $this->hasOne(Vehicle::class, 'current_driver_id');
    }
}
