<?php

namespace App\Filament\Resources\Vehicles\Widgets;

use App\Models\Trip;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class VehicleListStats extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected int|string|array $columnStart = 2;

    protected function getStats(): array
    {
        $companyId = Auth::id();

        $activeTrips = Trip::where('status', 'ongoing')
            ->whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
            ->count();

        $kmToday = Trip::whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
            ->where(fn($q) => $q->whereDate('ended_at', today())
                ->orWhere('status', 'ongoing'))
            ->sum('distance_km');

        return [
            Stat::make('Viagens Ativas', $activeTrips)
                ->description('Veículos em rota agora')
                ->descriptionIcon('heroicon-m-truck')
                ->color('success')
                ->chart([1, 3, 2, 5, 4, 6, $activeTrips]),

            Stat::make('KM Rodados Hoje', number_format($kmToday, 1, ',', '.') . ' km')
                ->description('Total percorrido hoje')
                ->descriptionIcon('heroicon-m-arrows-right-left')
                ->color('info')
                ->chart([10, 25, 45, 30, 60, 80, $kmToday]),
        ];
    }
}
