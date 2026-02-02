@php
    $warning = $getRecord();
    $vehicleId = $warning->vehicle_id;
@endphp

@if($vehicleId)
    @livewire(\App\Filament\Widgets\RouteWidget::class, [
        'vehicleId' => $vehicleId,
        'warningData' => [
            'latitude' => $warning->latitude,
            'longitude' => $warning->longitude,
            'type' => $warning->type,
            'severity' => $warning->severity,
            'occurred_at' => $warning->occurred_at->format('d/m/Y H:i'),
            'description' => $warning->description,
        ]
    ], key('route-' . $warning->id))
@else
    <div class="text-center py-8 text-gray-500">
        Nenhum veículo associado
    </div>
@endif