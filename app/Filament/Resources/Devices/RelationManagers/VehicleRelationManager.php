<?php

namespace App\Filament\Resources\Devices\RelationManagers;

use App\Filament\Resources\Vehicles\VehicleResource;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehicleRelationManager extends RelationManager
{
    protected static string $relationship = 'vehicle';

    protected static ?string $relatedResource = VehicleResource::class;

    protected static ?string $title = 'Veículo';

    public function table(Table $table): Table
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

                TextColumn::make('currentDriver.name')
                    ->label('Motorista')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('current_speed')
                    ->label('Velocidade (km/h)')
                    ->numeric()
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
            ->heading(null)
            ->recordTitleAttribute('name')
            ->paginated(false)
            ->selectable(false)
            ->searchable(false)
            ->toolbarActions([
                Action::make('info')
                    ->label('Veículo')
                    ->disabled()
                    ->color('inherit')
                    ->extraAttributes([
                        'class' => 'cursor-default px-0 py-2 font-semibold text-foreground',
                    ]),
            ])
            ->headerActions([]);
    }
}
