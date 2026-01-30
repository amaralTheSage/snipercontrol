<?php

namespace App\Filament\Resources\Vehicles\RelationManagers;

use App\Filament\Resources\Devices\DeviceResource;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeviceRelationManager extends RelationManager
{
    protected static string $relationship = 'device';

    protected static ?string $title = 'Dispositivo';

    protected static ?string $relatedResource = DeviceResource::class;

    public function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('serial')
                    ->label('Número de Série')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'online' => 'success',
                        'offline' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'online' => 'Online',
                        'offline' => 'Offline',
                        default => $state,
                    })->toggleable()
                    ->sortable(),

                TextColumn::make('last_communication_at')
                    ->label('Última Comunicação')
                    ->dateTime('d/m/Y H:i')->toggleable()
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

            ->headerActions([]);
    }
}
