<?php

namespace App\Filament\Resources\Devices\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('serial')
                    ->required(),
                TextInput::make('vehicle_id')
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('offline'),
                DateTimePicker::make('last_communication_at'),
            ]);
    }
}
