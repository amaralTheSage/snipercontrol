<?php

namespace App\Filament\Resources\Warnings\Pages;

use App\Filament\Resources\Warnings\WarningResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWarning extends ViewRecord
{
    protected static string $resource = WarningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
