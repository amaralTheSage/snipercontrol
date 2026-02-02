<?php

namespace App\Filament\Resources\Devices\Schemas;

use App\Filament\Widgets\RouteWidget;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class DeviceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Device Information Section
                Livewire::make(RouteWidget::class, fn($record) => [
                    'vehicleId' => $record->vehicle_id,
                ])->columnSpanFull(),

                Section::make('Informações do Dispositivo')
                    ->icon('heroicon-o-cpu-chip')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('serial')
                            ->label('Número de Série')
                            ->badge()
                            ->color('primary')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-hashtag')
                            ->copyable()
                            ->copyMessage('Número de série copiado!'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->size(TextSize::Large)
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'online' => 'Online',
                                'offline' => 'Offline',
                                default => $state,
                            })
                            ->color(fn(string $state): string => match ($state) {
                                'online' => 'success',
                                'offline' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn(string $state): string => match ($state) {
                                'online' => 'heroicon-o-signal',
                                'offline' => 'heroicon-o-signal-slash',
                                default => 'heroicon-o-question-mark-circle',
                            }),
                    ]),

                // Vehicle Information Section
                Section::make('Veículo Vinculado')
                    ->icon('heroicon-o-truck')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('vehicle.plate')
                            ->label('Placa')
                            ->placeholder('Nenhum veículo')
                            ->badge()
                            ->color('primary')
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-hashtag'),

                        TextEntry::make('vehicle.model')
                            ->label('Modelo')
                            ->placeholder('-')
                            ->icon('heroicon-o-cube')
                            ->weight(FontWeight::Medium),

                        TextEntry::make('vehicle.type')
                            ->label('Tipo')
                            ->placeholder('-')
                            ->badge()
                            ->formatStateUsing(fn(?string $state): string => match ($state) {
                                'truck' => 'Caminhão',
                                'van' => 'Van',
                                'car' => 'Carro',
                                'pickup' => 'Pickup',
                                null => '-',
                                default => $state,
                            })
                            ->color(fn(?string $state): string => match ($state) {
                                'truck' => 'info',
                                'van' => 'success',
                                'car' => 'warning',
                                'pickup' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('vehicle.status')
                            ->label('Status do Veículo')
                            ->placeholder('-')
                            ->badge()
                            ->formatStateUsing(fn(?string $state): string => match ($state) {
                                'active' => 'Ativo',
                                'maintenance' => 'Manutenção',
                                'blocked' => 'Bloqueado',
                                null => '-',
                                default => $state,
                            })
                            ->color(fn(?string $state): string => match ($state) {
                                'active' => 'success',
                                'maintenance' => 'warning',
                                'blocked' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('vehicle.currentDriver.name')
                            ->label('Motorista')
                            ->placeholder('Nenhum motorista')
                            ->icon('heroicon-o-user')
                            ->color('primary')
                            ->weight(FontWeight::SemiBold),
                    ])
                    ->hidden(fn($record) => ! $record->vehicle_id)
                    ->collapsible(),

                // Communication Section
                Section::make('Comunicação')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('last_communication_at')
                            ->label('Última Comunicação')
                            ->icon('heroicon-o-clock')
                            ->dateTime('d/m/Y H:i:s')
                            ->placeholder('Nunca se comunicou')
                            ->since()
                            ->color(fn($state) => $state && $state->diffInHours(now()) > 24 ? 'danger' : 'success')
                            ->weight(FontWeight::SemiBold),

                        // TextEntry::make('connection_quality')
                        //     ->label('Qualidade da Conexão')
                        //     ->placeholder('N/A')
                        //     ->state(function ($record) {
                        //         if (!$record->last_communication_at) {
                        //             return null;
                        //         }

                        //         $hours = $record->last_communication_at->diffInHours(now());

                        //         if ($hours < 1) return 'Excelente';
                        //         if ($hours < 6) return 'Boa';
                        //         if ($hours < 24) return 'Regular';
                        //         return 'Ruim';
                        //     })
                        //     ->badge()
                        //     ->color(function ($state) {
                        //         return match ($state) {
                        //             'Excelente' => 'success',
                        //             'Boa' => 'info',
                        //             'Regular' => 'warning',
                        //             'Ruim' => 'danger',
                        //             default => 'gray',
                        //         };
                        //     })
                        //     ->icon(function ($state) {
                        //         return match ($state) {
                        //             'Excelente' => 'heroicon-o-signal',
                        //             'Boa' => 'heroicon-o-signal',
                        //             'Regular' => 'heroicon-o-signal',
                        //             'Ruim' => 'heroicon-o-signal-slash',
                        //             default => 'heroicon-o-question-mark-circle',
                        //         };
                        //     }),
                    ]),


            ]);
    }
}
