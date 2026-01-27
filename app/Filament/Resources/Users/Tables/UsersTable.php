<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')->circular()->label('Usuário')->width('4%'),
                TextColumn::make('name')->label(' ')
                    ->searchable(),
        
        TextColumn::make('email')
            ->label('E-mail')
            ->searchable(),

        TextColumn::make('email_verified_at')
            ->label('E-mail verificado em')
            ->dateTime()
            ->sortable(),

        TextColumn::make('two_factor_confirmed_at')
            ->label('2FA confirmado em')
            ->dateTime()
            ->sortable(),

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
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
