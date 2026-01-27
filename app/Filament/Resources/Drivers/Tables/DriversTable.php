<?php

namespace App\Filament\Resources\Drivers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DriversTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([ 
                ImageColumn::make('avatar_url')
                    ->label(' ')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'active'   => 'success',
                        'inactive' => 'gray',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'active'   => 'Ativo',
                        'inactive' => 'Inativo',
                        default    => $state,
                    }),

                TextColumn::make('currentVehicle.plate')
                    ->label('Placa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('currentVehicle.model')
                    ->label('Veículo')
                    ->sortable(),

                IconColumn::make('currentVehicle.ignition_on')
                    ->label('Veículo Ligado')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
