<?php

namespace App\Filament\Widgets;

use App\Models\TelemetryEvent;
use App\Models\Vehicle;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FuelConsumptionChart extends ChartWidget
{
    protected ?string $heading = 'Consumo de Combustível Hoje';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = '1';

    protected function getMaxHeight(): ?string
    {
        return '250px';
    }

    protected function getData(): array
    {
        $companyId = Auth::id();

        // PostgreSQL-compatible query
        $fuelData = TelemetryEvent::query()
            ->whereHas('trip.vehicle', fn($q) => $q->where('company_id', $companyId))
            ->whereDate('recorded_at', today())
            ->whereNotNull('fuel')
            ->select(
                DB::raw('EXTRACT(HOUR FROM recorded_at) as hour'),
                DB::raw('AVG(fuel) as avg_fuel')
            )
            ->groupBy(DB::raw('EXTRACT(HOUR FROM recorded_at)'))
            ->orderBy(DB::raw('EXTRACT(HOUR FROM recorded_at)'))
            ->get()
            ->pluck('avg_fuel', 'hour');

        // Preencher todas as horas até agora
        $hours = collect(range(0, now()->hour))->map(function ($hour) use ($fuelData) {
            return round($fuelData->get($hour, 0), 1);
        });

        // Se não houver dados de telemetria, usar valores dos veículos
        if ($hours->sum() == 0) {
            return $this->getFallbackData($companyId);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Nível Médio de Combustível (%)',
                    'data' => $hours->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
            ],
            'labels' => collect(range(0, now()->hour))->map(fn($h) => sprintf('%02d:00', $h))->toArray(),
        ];
    }

    protected function getFallbackData(int $companyId): array
    {
        // Fallback: usar fuel_level atual dos veículos e simular consumo ao longo do dia
        $avgFuel = Vehicle::where('company_id', $companyId)
            ->whereNotNull('fuel_level')
            ->avg('fuel_level') ?? 75;

        $hours = collect(range(0, now()->hour))->map(function ($hour) use ($avgFuel) {
            // Simular consumo gradual ao longo do dia
            return max(0, $avgFuel - ($hour * 1.5));
        });

        return [
            'datasets' => [
                [
                    'label' => 'Nível Médio de Combustível (%) - Estimado',
                    'data' => $hours->toArray(),
                    'borderColor' => '#9ca3af',
                    'backgroundColor' => 'rgba(156, 163, 175, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'borderDash' => [5, 5], // Linha tracejada para indicar estimativa
                ],
            ],
            'labels' => collect(range(0, now()->hour))->map(fn($h) => sprintf('%02d:00', $h))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'max' => 100,
                    'ticks' => [
                        'callback' => 'function(value) { return value + "%"; }',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return "Combustível: " + context.parsed.y.toFixed(1) + "%"; }',
                    ],
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }
}
