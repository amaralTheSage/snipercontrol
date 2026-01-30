<?php

namespace App\Filament\Resources\Devices\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('serial')
                    ->label('Número de Série')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Select::make('vehicle_id')
                    ->label('Veículo')
                    ->relationship('vehicle', 'plate')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->plate.' - '.$record->model)
                    ->placeholder('Nenhum veículo'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'online' => 'Online',
                        'offline' => 'Offline',
                    ])
                    ->default('offline')
                    ->required(),

            ]);
    }
}
