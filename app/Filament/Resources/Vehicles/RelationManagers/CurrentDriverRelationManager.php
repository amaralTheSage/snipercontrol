<?php

namespace App\Filament\Resources\Vehicles\RelationManagers;

use App\Filament\Resources\Drivers\DriverResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CurrentDriverRelationManager extends RelationManager
{
    protected static string $relationship = 'currentDriver';

    protected static ?string $title = 'Motorista';

    protected static ?string $relatedResource = DriverResource::class;


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
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
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
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
