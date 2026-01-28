<?php

namespace App\Filament\Resources\Drivers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar')
                    ->hiddenLabel()
                    ->image()
                    ->avatar()
                    ->directory('avatars')
                    ->imageEditor()
                    ->circleCropper()
                    ->nullable(),

                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),

                TextInput::make('cpf')
                    ->label('CPF')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->mask('999.999.999-99')
                    ->placeholder('000.000.000-00')
                    ->maxLength(14),

                TextInput::make('phone')
                    ->label('Telefone')
                    ->tel()
                    ->mask('(99) 99999-9999')
                    ->placeholder('(00) 00000-0000')
                    ->maxLength(15),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }
}
