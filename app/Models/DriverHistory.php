<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverHistory extends Model
{
    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    // Relationships
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull('ended_at');
    }

    public function scopeEnded($query)
    {
        return $query->whereNotNull('ended_at');
    }

    public function scopeForVehicle($query, int $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }

    public function scopeForDriver($query, int $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    /**
     * Scope para encontrar quem estava dirigindo em um momento específico
     */
    public function scopeAtTime($query, Carbon $timestamp)
    {
        return $query->where('started_at', '<=', $timestamp)
            ->where(function ($q) use ($timestamp) {
                $q->whereNull('ended_at')
                    ->orWhere('ended_at', '>', $timestamp);
            });
    }

    /**
     * Scope para encontrar atribuições em um período
     */
    public function scopeBetween($query, Carbon $start, Carbon $end)
    {
        return $query->where(function ($q) use ($start, $end) {
            $q->whereBetween('started_at', [$start, $end])
                ->orWhereBetween('ended_at', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('started_at', '<=', $start)
                        ->where(function ($q3) use ($end) {
                            $q3->whereNull('ended_at')
                                ->orWhere('ended_at', '>=', $end);
                        });
                });
        });
    }

    // Helpers
    public function isActive(): bool
    {
        return $this->ended_at === null;
    }

    public function getDurationAttribute(): ?int
    {
        if (!$this->ended_at) {
            return null;
        }

        return $this->started_at->diffInMinutes($this->ended_at);
    }

    public function getDurationHumanAttribute(): string
    {
        if (!$this->ended_at) {
            return 'Atual';
        }

        $duration = $this->duration;

        if ($duration < 60) {
            return $duration . ' min';
        }

        $hours = floor($duration / 60);
        $minutes = $duration % 60;

        if ($hours < 24) {
            return $hours . 'h ' . $minutes . 'min';
        }

        $days = floor($hours / 24);
        $remainingHours = $hours % 24;

        return $days . 'd ' . $remainingHours . 'h';
    }
}
