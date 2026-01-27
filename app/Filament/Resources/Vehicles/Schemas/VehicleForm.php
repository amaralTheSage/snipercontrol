<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('plate')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('year')
                    ->required()
                    ->numeric(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                TextInput::make('current_driver_id')
                    ->numeric(),
                TextInput::make('current_speed')
                    ->numeric(),
                TextInput::make('fuel_level')
                    ->numeric(),
                Toggle::make('ignition_on')
                    ->required(),
                Toggle::make('relay_enabled')
                    ->required(),
                TextInput::make('last_latitude')
                    ->numeric(),
                TextInput::make('last_longitude')
                    ->numeric(),
                DateTimePicker::make('last_update_at'),
            ]);
    }
}
