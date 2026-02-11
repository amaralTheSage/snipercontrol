<?php

namespace App\Filament\Resources\Warnings\Pages;

use App\Filament\Resources\Warnings\Widgets\WarningSeverityStats;
use App\Filament\Resources\Warnings\WarningResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListWarnings extends ListRecords
{
    protected static string $resource = WarningResource::class;

    public function getTabs(): array
    {
        return [
            'unresolved' => Tab::make('Unresolved')->label('Não Resolvidos')
                ->modifyQueryUsing(
                    fn(Builder $query) => $query->whereNull('resolved_at')
                ),

            'resolved' => Tab::make('Resolved')->label('Resolvidos')
                ->modifyQueryUsing(
                    fn(Builder $query) => $query->whereNotNull('resolved_at')
                ),

        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [WarningSeverityStats::class];
    }



    protected function getHeaderActions(): array
    {
        return [];
    }
}
