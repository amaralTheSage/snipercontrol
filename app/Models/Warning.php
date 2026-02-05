<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warning extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'driver_id',
        'vehicle_id',
        'latitude',
        'longitude',
        'severity',
        'occurred_at',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'resolved_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    // Relationships
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
    public function videoRecordings(): HasMany
    {
        return $this->hasMany(VideoRecording::class);
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Scopes
    public function scopeUnresolved($query)
    {
        return $query->whereNull('resolved_at');
    }

    public function scopeResolved($query)
    {
        return $query->whereNotNull('resolved_at');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeHighSeverity($query)
    {
        return $query->where('severity', 'high');
    }

    // Helpers
    public function isResolved(): bool
    {
        return $this->resolved_at !== null;
    }

    public function markAsResolved(?int $userId = null, ?string $notes = null): void
    {
        $this->update([
            'resolved_at' => now(),
            'resolved_by' => $userId ?? auth()->id(),
            'resolution_notes' => $notes,
        ]);
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'route_diversion' => 'Desvio de Rota',
            'cargo_theft' => 'Furto de Carga',
            'fuel_theft' => 'Furto de Combustível',
            'unexpected_stop' => 'Parada inesperada',
            default => $this->type,
        };
    }

    public function getSeverityLabel(): string
    {
        return match ($this->severity) {
            'low' => 'Baixa',
            'medium' => 'Média',
            'high' => 'Alta',
            default => $this->severity,
        };
    }
}
