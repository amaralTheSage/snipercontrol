<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
            ? asset('storage/'.$this->avatar)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->name);
    }

    public function currentVehicle()
    {
        return $this->hasOne(Vehicle::class, 'current_driver_id');
    }

    public function currentVehicleDevice()
    {
        return $this->hasOneThrough(Device::class, Vehicle::class, 'current_driver_id', 'vehicle_id');
    }

    public function warnings(): HasMany
    {
        return $this->hasMany(Warning::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id', 'id');
    }
}
