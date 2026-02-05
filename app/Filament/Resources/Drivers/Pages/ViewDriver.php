<?php

namespace App\Filament\Resources\Drivers\Pages;

use App\Filament\Resources\Drivers\DriverResource;
use App\Filament\Widgets\RouteWidget;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDriver extends ViewRecord
{
    protected static string $resource = DriverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            RouteWidget::make([
                'vehicleId' => $this->record->currentVehicle?->id,
            ]),
        ];
    }
}
