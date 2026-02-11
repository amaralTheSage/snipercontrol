<?php

namespace App\Filament\Widgets;

use App\Models\Trip;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WeeklyDistanceChart extends ChartWidget
{
    protected ?string $heading = 'Quilômetros Rodados na Semana';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = '1';

    protected function getMaxHeight(): ?string
    {
        return '250px';
    }

    protected function getData(): array
    {
        $companyId = Auth::id();
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        // 1. Fetch all trips for the week in ONE query
        // We use string comparison for the date since it might be stored as string in Mongo
        $trips = Trip::query()
            ->whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
            ->whereBetween('started_at', [
                $startDate->format('Y-m-d H:i:s'),
                $endDate->format('Y-m-d H:i:s')
            ])
            ->get();

        // 2. Process the data in memory (PHP)
        $weekData = collect(range(6, 0))->map(function ($daysAgo) use ($trips) {
            $targetDate = now()->subDays($daysAgo);
            $targetDateString = $targetDate->format('Y-m-d');

            // Filter the collection in memory
            $dailySum = $trips->filter(function ($trip) use ($targetDateString) {
                // Handle cases where started_at might be a Carbon object or a String
                $tripDate = $trip->started_at instanceof \Carbon\Carbon
                    ? $trip->started_at->format('Y-m-d')
                    : substr($trip->started_at, 0, 10);

                return $tripDate === $targetDateString;
            })->sum('distance_km'); // PHP collection sum handles strings correctly

            return [
                'short_day' => $targetDate->locale('pt_BR')->shortDayName,
                'distance' => round($dailySum, 1),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Distância (km)',
                    'data' => $weekData->pluck('distance')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6',
                        '#3b82f6',
                        '#3b82f6',
                        '#3b82f6',
                        '#3b82f6',
                        '#3b82f6',
                        '#10b981', // Hoje
                    ],
                    'borderColor' => '#2563eb',
                    'borderWidth' => 1, // Reduced width looks cleaner
                ],
            ],
            'labels' => $weekData->pluck('short_day')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return value + " km"; }',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.parsed.y + " km"; }',
                    ],
                ],
            ],
        ];
    }
}
