<?php

namespace App\Filament\Resources\Warnings\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WarningsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->getTypeLabel())
                    ->color(fn(string $state): string => match ($state) {
                        'route_diversion' => 'warning',
                        'cargo_theft' => 'danger',
                        'fuel_theft' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),

                // TextColumn::make('severity')
                //     ->label('Gravidade')
                //     ->badge()
                //     ->formatStateUsing(fn($record) => $record->getSeverityLabel())
                //     ->color(fn(string $state): string => match ($state) {
                //         'low' => 'success',
                //         'medium' => 'warning',
                //         'high' => 'danger',
                //         default => 'gray',
                //     })
                //     ->sortable(),

                ImageColumn::make('driver.avatar_url')
                    ->label('Motorista')
                    ->circular()
                    ->alignCenter()
                    ->width('2%')
                    ->placeholder('N/A'),

                TextColumn::make('driver.name')
                    ->label(' ')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),

                TextColumn::make('vehicle.plate')
                    ->label('Veículo')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A')
                    ->description(fn($record) => $record->vehicle?->model),

                TextColumn::make('location')
                    ->label('Local')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    })
                    ->icon('heroicon-o-map-pin')
                    ->placeholder('N/A'),

                IconColumn::make('resolved_at')
                    ->label('Resolvido')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('occurred_at')
                    ->label('Data/Hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->description(fn($record) => $record->occurred_at->diffForHumans()),

                TextColumn::make('resolver.name')
                    ->label('Resolvido por')
                    ->placeholder('Não resolvido')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-o-user-circle'),

                TextColumn::make('resolved_at')
                    ->label('Data Resolução')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Pendente')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'route_diversion' => 'Desvio de Rota',
                        'cargo_theft' => 'Roubo de Carga',
                        'fuel_theft' => 'Roubo de Combustível',
                    ])
                    ->multiple(),

                SelectFilter::make('severity')
                    ->label('Gravidade')
                    ->options([
                        'low' => 'Baixa',
                        'medium' => 'Média',
                        'high' => 'Alta',
                    ])
                    ->multiple(),


                Filter::make('occurred_at')
                    ->schema([
                        \Filament\Forms\Components\DatePicker::make('occurred_from')
                            ->label('Ocorrido de'),
                        \Filament\Forms\Components\DatePicker::make('occurred_until')
                            ->label('Ocorrido até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['occurred_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('occurred_at', '>=', $date),
                            )
                            ->when(
                                $data['occurred_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('occurred_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['occurred_from'] ?? null) {
                            $indicators[] = 'De: ' . \Carbon\Carbon::parse($data['occurred_from'])->format('d/m/Y');
                        }

                        if ($data['occurred_until'] ?? null) {
                            $indicators[] = 'Até: ' . \Carbon\Carbon::parse($data['occurred_until'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([
                ViewAction::make()->hiddenLabel(),
                Action::make('resolve')
                    ->label('Resolver')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => !$record->isResolved())
                    ->schema([
                        Textarea::make('resolution_notes')
                            ->label('Notas de Resolução')
                            ->rows(3)
                            ->placeholder('Descreva como o problema foi resolvido...')
                            ->maxLength(1000),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->markAsResolved(
                            userId: auth()->id(),
                            notes: $data['resolution_notes'] ?? null
                        );

                        \Filament\Notifications\Notification::make()
                            ->title('Aviso resolvido com sucesso')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Resolver Aviso')
                    ->modalDescription('Marcar este aviso como resolvido?')
                    ->modalSubmitActionLabel('Resolver'),

                // Action::make('view_on_map')
                //     ->label('Ver no Mapa')
                //     ->icon('heroicon-o-map')
                //     ->color('info')
                //     ->visible(fn($record) => $record->latitude && $record->longitude)
                //     ->url(fn($record) => route('filament.admin.pages.driver-map') . '?lat=' . $record->latitude . '&lng=' . $record->longitude)
                //     ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),

                    BulkAction::make('mark_as_resolved')
                        ->label('Marcar como Resolvido')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->schema([
                            Textarea::make('resolution_notes')
                                ->label('Notas de Resolução')
                                ->rows(3)
                                ->placeholder('Descreva como os problemas foram resolvidos...')
                                ->maxLength(1000),
                        ])
                        ->action(function ($records, array $data): void {
                            foreach ($records as $record) {
                                if (!$record->isResolved()) {
                                    $record->markAsResolved(
                                        userId: auth()->id(),
                                        notes: $data['resolution_notes'] ?? null
                                    );
                                }
                            }

                            \Filament\Notifications\Notification::make()
                                ->title('Avisos resolvidos com sucesso')
                                ->body(count($records) . ' aviso(s) marcado(s) como resolvido(s)')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('Nenhum aviso encontrado')
            ->emptyStateDescription('Não há avisos cadastrados no momento.')
            ->emptyStateIcon('heroicon-o-bell-alert');
    }
}
