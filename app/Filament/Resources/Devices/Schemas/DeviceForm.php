<?php

namespace App\Filament\Resources\Devices\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Auth;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    TextInput::make('mac_address')
                        ->label('Endereço MAC')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->macAddress()
                        ->placeholder('00:00:00:00:00:00'),

                    Hidden::make('company_id')
                        ->default(Auth::id()),

                    Select::make('vehicle_id')
                        ->label('Veículo')
                        ->relationship('vehicle', 'plate')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->plate . ' - ' . $record->model)
                        ->placeholder('Nenhum veículo'),

                    // Select::make('status')
                    //     ->label('Status')
                    //     ->options([
                    //         'online' => 'Online',
                    //         'offline' => 'Offline',
                    //     ])
                    //     ->default('offline')
                    //     ->required(),

                ]),
            ])->columns(1);
    }
}
