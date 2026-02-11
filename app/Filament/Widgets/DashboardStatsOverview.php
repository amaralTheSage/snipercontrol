<?php

namespace App\Filament\Widgets;

use App\Models\Trip;
use App\Models\Vehicle;
use App\Models\Warning;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DashboardStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $companyId = Auth::id();

        // 1. Avisos não resolvidos (total)
        $unresolvedWarnings = Warning::whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
            ->unresolved()
            ->count();

        // 2. Avisos não resolvidos de alta severidade
        $criticalWarnings = Warning::whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
            ->unresolved()
            ->where('severity', 'high')
            ->count();

        // 3. Total de veículos + em manutenção
        $totalVehicles = Vehicle::where('company_id', $companyId)->count();
        $maintenanceVehicles = Vehicle::where('company_id', $companyId)
            ->where('status', 'maintenance')
            ->count();

        // 5. Viagens em andamento
        $ongoingTrips = Trip::whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
            ->where('status', 'ongoing')
            ->count();

        return [
            Stat::make('Avisos Não Resolvidos', $unresolvedWarnings)
                ->description('Pendentes de resolução')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($unresolvedWarnings > 0 ? 'warning' : 'success')
                ->chart([2, 3, 4, 6, 5, 7, 9]),

            Stat::make('Avisos Críticos', $criticalWarnings)
                ->description('Alta severidade')
                ->descriptionIcon('heroicon-o-fire')
                ->color($criticalWarnings > 0 ? 'danger' : 'success')
                ->chart([3, 1, 0, 2, 2, 1, 0])
                ->extraAttributes([
                    'class' => $criticalWarnings > 0 ? 'animate-pulse' : '',
                ]),

            Stat::make('Veículos', $totalVehicles)
                ->description(
                    $maintenanceVehicles > 0
                        ? "{$maintenanceVehicles} em manutenção"
                        : 'Todos operacionais'
                )
                ->descriptionIcon(
                    $maintenanceVehicles > 0
                        ? 'heroicon-o-wrench'
                        : 'heroicon-o-check-circle'
                )
                ->color($maintenanceVehicles > 0 ? 'warning' : 'primary')
                ->chart([3, 5, 4, 6, 5, 7, 5]),


            Stat::make('Viagens Ativas', $ongoingTrips)
                ->description('no momento')
                ->descriptionIcon('heroicon-o-truck')
                ->color($ongoingTrips > 0 ? 'success' : 'gray')
                ->chart([1, 3, 2, 5, 4, 6]),
        ];
    }

    protected function getWarningsChart(): array
    {
        $companyId = Auth::id();

        // Últimos 7 dias
        return collect(range(6, 0))->map(function ($daysAgo) use ($companyId) {
            return Warning::whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
                ->whereDate('occurred_at', now()->subDays($daysAgo))
                ->count();
        })->toArray();
    }

    protected function getTripsChart(): array
    {
        $companyId = Auth::id();

        // Últimos 7 dias
        return collect(range(6, 0))->map(function ($daysAgo) use ($companyId) {
            return Trip::whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
                ->whereDate('started_at', now()->subDays($daysAgo))
                ->count();
        })->toArray();
    }
}
