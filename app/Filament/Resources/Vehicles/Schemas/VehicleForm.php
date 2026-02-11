<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Group::make([
                    TextInput::make('plate')
                        ->label('Placa')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('model')
                        ->label('Modelo')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('year')
                        ->label('Ano')
                        ->required()
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue(date('Y') + 1),

                ]),

                // Device Select

                Section::make('Associados')->schema([
                    Select::make('device_id')
                        ->label('Dispositivo')
                        ->relationship('device', 'mac_address')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->createOptionForm([
                            TextInput::make('mac_address')
                                ->label('Endereço MAC')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ])
                        ->createOptionAction(function (Action $action) {
                            return $action
                                ->modalHeading('Criar Dispositivo')
                                ->modalWidth(Width::Medium);
                        }),

                    Select::make('current_driver_id')
                        ->label('Motorista Atual')
                        ->relationship('currentDriver', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->name . ' - ' . $record->cpf)
                        ->createOptionForm([
                            FileUpload::make('avatar')
                                ->hiddenLabel()
                                ->image()
                                ->avatar()->alignCenter()
                                ->directory('avatars')
                                ->nullable(),

                            TextInput::make('name')
                                ->label('Nome')
                                ->required(),

                            TextInput::make('cpf')
                                ->label('CPF')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->mask('999.999.999-99')
                                ->placeholder('000.000.000-00'),

                            TextInput::make('phone')
                                ->label('Telefone')
                                ->tel()
                                ->mask('(99) 99999-9999')
                                ->placeholder('(00) 00000-0000'),

                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'active' => 'Ativo',
                                    'inactive' => 'Inativo',
                                ])
                                ->default('active')
                                ->required(),
                        ])
                        ->createOptionAction(function (Action $action) {
                            return $action
                                ->modalHeading('Criar Motorista')
                                ->modalWidth(Width::Large);
                        }),

                ]),

                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'truck' => 'Caminhão',
                        'van' => 'Van',
                        'car' => 'Carro',
                        'pickup' => 'Caminhonete',
                    ])
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Ativo',
                        'maintenance' => 'Manutenção',
                        'blocked' => 'Bloqueado',
                    ])
                    ->default('active')
                    ->required(),

                TextInput::make('fuel_level')
                    ->label('Nível de Combustível')
                    ->numeric()
                    ->suffix('%')
                    ->minValue(0)
                    ->maxValue(100),

            ]);
    }
}
