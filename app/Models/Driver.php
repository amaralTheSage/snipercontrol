<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name',
        'cpf',
        'phone',
        'avatar',
        'status',
    ];


    // --------------------------
    //  Filament Stuff
    // --------------------------


public function getAvatarUrlAttribute(): string
{
    return $this->avatar
        ? asset('storage/' . $this->avatar)
        : 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
}

    public function currentVehicle()
    {
        return $this->hasOne(Vehicle::class, 'current_driver_id');
    }
}
