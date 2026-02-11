<?php

namespace App\Filament\Resources\Warnings\Widgets;

use App\Models\Warning;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class WarningSeverityStats extends BaseWidget
{
    protected function getStats(): array
    {
        $companyId = Auth::id();

        // Base query to reuse logic
        $baseQuery = Warning::query()
            ->whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
            ->unresolved();

        // Count for High, Medium, Low
        $highCount = (clone $baseQuery)->where('severity', 'high')->count();
        $mediumCount = (clone $baseQuery)->where('severity', 'medium')->count();
        $lowCount = (clone $baseQuery)->where('severity', 'low')->count();

        return [
            Stat::make('Críticos (Alta)', $highCount)
                ->description('Requer ação imediata')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($highCount > 0 ? 'danger' : 'success')
                ->chart($this->getSeverityTrend('high', $companyId))
                // Generates: /dash/warnings?filters[severity][values][0]=high
                ->url(route('filament.dash.resources.warnings.index', [
                    'filters' => ['severity' => ['values' => ['high']]]
                ])),

            Stat::make('Atenção (Média)', $mediumCount)
                ->description('Monitorar situação')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color($mediumCount > 0 ? 'warning' : 'gray')
                ->chart($this->getSeverityTrend('medium', $companyId))
                ->url(route('filament.dash.resources.warnings.index', [
                    'filters' => ['severity' => ['values' => ['medium']]]
                ])),

            Stat::make('Informativos (Baixa)', $lowCount)
                ->description('Ocorrências leves')
                ->descriptionIcon('heroicon-m-information-circle')
                ->color('info')
                ->chart($this->getSeverityTrend('low', $companyId))
                ->url(route('filament.dash.resources.warnings.index', [
                    'filters' => ['severity' => ['values' => ['low']]]
                ])),
        ];
    }

    protected function getSeverityTrend(string $severity, int $companyId): array
    {
        return collect(range(6, 0))->map(function ($daysAgo) use ($severity, $companyId) {
            return Warning::whereHas('vehicle', fn($q) => $q->where('company_id', $companyId))
                ->where('severity', $severity)
                ->whereDate('occurred_at', now()->subDays($daysAgo))
                ->count();
        })->toArray();
    }
}
