<?php

namespace App\Filament\Resources\Devices\RelationManagers;

use App\Filament\Resources\Drivers\DriverResource;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CurrentVehicleDriverRelationManager extends RelationManager
{
    protected static string $relationship = 'currentVehicleDriver';

    protected static ?string $relatedResource = DriverResource::class;

    protected static ?string $title = 'Motorista';

    public function table(Table $table): Table
    {
        return $table

            ->columns([
                ImageColumn::make('avatar_url')->circular()->label('Motorista')->width('4%'),

                TextColumn::make('name')
                    ->label(' ')
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
                    ->color(fn (string $state) => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                        default => $state,
                    }),

                TextColumn::make('currentVehicle.plate')
                    ->label('Veículo')
                    ->searchable()
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
                    ->label('Motorista')
                    ->disabled()
                    ->color('inherit')
                    ->extraAttributes([
                        'class' => 'cursor-default px-0 py-2 font-semibold text-foreground',
                    ]),
            ])
            ->headerActions([]);
    }
}
