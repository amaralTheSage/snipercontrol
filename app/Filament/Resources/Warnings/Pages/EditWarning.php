<?php

namespace App\Filament\Resources\Warnings\Pages;

use App\Filament\Resources\Warnings\WarningResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWarning extends EditRecord
{
    protected static string $resource = WarningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
