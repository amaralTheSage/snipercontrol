<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Vehicles\VehicleResource;
use App\Filament\Resources\Warnings\WarningResource;
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

        // 1. Avisos não resolvidos
        $unresolvedWarnings = Warning::whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
            ->unresolved()
            ->count();

        // 2. Avisos Críticos
        $criticalWarnings = Warning::whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
            ->unresolved()
            ->where('severity', 'high')
            ->count();

        // 3. Veículos
        $totalVehicles = Vehicle::where('company_id', $companyId)->count();
        $maintenanceVehicles = Vehicle::where('company_id', $companyId)
            ->where('status', 'maintenance')
            ->count();

        // 4. Viagens
        $ongoingTrips = Trip::whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
            ->where('status', 'ongoing')
            ->count();

        return [
            Stat::make('Avisos Não Resolvidos', $unresolvedWarnings)
                ->description('Pendentes de resolução')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($unresolvedWarnings > 0 ? 'warning' : 'success')
                ->chart([2, 3, 4, 6, 5, 7, 9])
                // Filtra por status 'unresolved' ou 'pending' (ajuste o nome do seu filtro)
                ->url(WarningResource::getUrl('index', [
                    'tab' => 'unresolved',
                ])),

            Stat::make('Avisos Críticos', $criticalWarnings)
                ->description('Alta severidade')
                ->descriptionIcon('heroicon-o-fire')
                ->color($criticalWarnings > 0 ? 'danger' : 'success')
                ->chart([3, 1, 0, 2, 2, 1, 0])
                ->extraAttributes([
                    'class' => $criticalWarnings > 0 ? 'animate-pulse' : '',
                ])
                // Filtra por severidade High
                ->url(WarningResource::getUrl('index', [
                    'filters[severity][values][0]' => 'high',
                    'tab' => 'unresolved',
                ])),

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
                ->chart([3, 5, 4, 6, 5, 7, 5])
                // Redireciona para a lista de veículos (se quiser filtrar só os em manutenção, adicione o filtro abaixo)
                ->url(VehicleResource::getUrl('index', $maintenanceVehicles > 0 ? [
                    'filters[status][values][0]' => 'maintenance'
                ] : [])),

            Stat::make('Viagens Ativas', $ongoingTrips)
                ->description('no momento')
                ->descriptionIcon('heroicon-o-truck')
                ->color($ongoingTrips > 0 ? 'success' : 'gray')
                ->chart([1, 3, 2, 5, 4, 6])
                ->url("/dash/map"),
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
