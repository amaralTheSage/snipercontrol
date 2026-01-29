<?php

namespace App\Filament\Resources\Drivers\RelationManagers;

use App\Filament\Resources\Devices\DeviceResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CurrentVehicleDeviceRelationManager extends RelationManager
{
    protected static string $relationship = 'currentVehicleDevice';

    protected static ?string $relatedResource = DeviceResource::class;

    protected static ?string $title = 'Dispositivo';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('serial')
                    ->label('Serial')
                    ->searchable(),

                TextColumn::make('vehicle.plate')
                    ->label('Veículo')
                    ->searchable()
                    ->sortable(),


                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'online'  => 'success',
                        'offline' => 'danger',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'online'  => 'Online',
                        'offline' => 'Offline',
                        default   => $state,
                    })
                    ->sortable(),

                TextColumn::make('last_communication_at')
                    ->label('Última Comunicação')
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
            ->recordTitleAttribute('serial')
            ->paginated(false)
            ->searchable(false)
            ->selectable(false)
            ->toolbarActions([
                Action::make('info')
                    ->label('Dispositivo')
                    ->disabled()
                    ->color('inherit')
                    ->extraAttributes([
                        'class' => 'cursor-default px-0 py-2 font-semibold text-foreground',
                    ]),
            ])

            ->headerActions([]);;
    }
}
