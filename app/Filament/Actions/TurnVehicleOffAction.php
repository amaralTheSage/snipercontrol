<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Colors\Color;

class TurnVehicleOffAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'turnOffVehicle';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Desligar Veículo')
            ->icon('heroicon-o-exclamation-triangle')
            ->color(Color::Amber)
            ->requiresConfirmation()
            ->action(function () {});
    }
}
