<?php

namespace App\Filament\Resources\Vehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plate')
                    ->label('Placa')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('model')
                    ->label('Modelo')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('year')
                    ->label('Ano')
                    ->numeric()
                    ->toggleable()
                    ->toggledHiddenByDefault(condition: true)
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'truck' => 'Caminhão',
                        'van' => 'Van',
                        'car' => 'Carro',
                        'pickup' => 'Caminhonete',
                        default => $state,
                    })
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->toggleable()
                    ->color(fn(string $state) => match ($state) {
                        'active' => 'success',
                        'maintenance' => 'warning',
                        'blocked' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'active' => 'Ativo',
                        'maintenance' => 'Manutenção',
                        'blocked' => 'Bloqueado',
                        default => $state,
                    }),

                ImageColumn::make('currentDriver.avatar_url')
                    ->label('Motorista')
                    ->circular()
                    ->imageSize(36)
                    ->width('4%')
                    ->defaultImageUrl(fn() => ''),

                TextColumn::make('currentDriver.name')
                    ->label(' ')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('fuel_level')
                    ->label('Combustível (%)')
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),

                IconColumn::make('ignition_on')
                    ->label('Ignição')
                    ->boolean(),

                TextColumn::make('last_latitude')
                    ->label('Latitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('last_longitude')
                    ->label('Longitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('last_update_at')
                    ->label('Última Atualização')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

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
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Ativo',
                        'maintenance' => 'Manutenção',
                        'blocked' => 'Bloqueado',
                    ])
                    ->multiple(),
            ])
            ->recordActions([
                ViewAction::make()->hiddenLabel(),
                EditAction::make()->hiddenLabel(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
