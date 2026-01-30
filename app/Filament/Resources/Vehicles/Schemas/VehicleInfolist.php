<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Filament\Widgets\RouteWidget;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\TextSize;

class VehicleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Livewire::make(RouteWidget::class, fn ($record) => [
                    'vehicleId' => $record->id,
                ])
                    ->columnSpanFull(),

                // Vehicle Information Section
                Section::make('Informações do Veículo')
                    ->icon('heroicon-o-truck')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('plate')
                            ->label('Placa')
                            ->badge()
                            ->color('primary')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold),

                        TextEntry::make('model')
                            ->label('Modelo')
                            ->icon('heroicon-o-cube')
                            ->weight(FontWeight::Bold),

                        TextEntry::make('year')
                            ->label('Ano')
                            ->icon('heroicon-o-calendar')
                            ->numeric(),

                        TextEntry::make('type')
                            ->label('Tipo')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'truck' => 'Caminhão',
                                'van' => 'Van',
                                'car' => 'Carro',
                                'pickup' => 'Pickup',
                                default => $state,
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'truck' => 'info',
                                'van' => 'success',
                                'car' => 'warning',
                                'pickup' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn (string $state): string => match ($state) {
                                'truck' => 'heroicon-o-truck',
                                'van' => 'heroicon-o-building-office',
                                'car' => 'heroicon-m-home',
                                'pickup' => 'heroicon-o-cube-transparent',
                                default => 'heroicon-o-question-mark-circle',
                            }),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'active' => 'Ativo',
                                'maintenance' => 'Manutenção',
                                'blocked' => 'Bloqueado',
                                default => $state,
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'maintenance' => 'warning',
                                'blocked' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn (string $state): string => match ($state) {
                                'active' => 'heroicon-o-check-circle',
                                'maintenance' => 'heroicon-o-wrench-screwdriver',
                                'blocked' => 'heroicon-o-x-circle',
                                default => 'heroicon-o-question-mark-circle',
                            }),

                        TextEntry::make('currentDriver.name')
                            ->label('Motorista Atual')
                            ->icon('heroicon-o-user')
                            ->placeholder('Nenhum motorista')
                            ->weight(FontWeight::SemiBold)
                            ->color('primary'),
                    ]),

                // Current Status Section
                Section::make('Status Atual')
                    ->icon('heroicon-o-signal')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('current_speed')
                            ->label('Velocidade')
                            ->suffix(' km/h')
                            ->icon('heroicon-o-bolt')
                            ->placeholder('0 km/h')
                            ->color(fn ($state) => match (true) {
                                $state > 80 => 'danger',
                                $state > 60 => 'warning',
                                default => 'success',
                            })
                            ->weight(FontWeight::Bold),

                        TextEntry::make('fuel_level')
                            ->label('Combustível')
                            ->suffix('%')
                            ->icon('heroicon-o-bolt')
                            ->placeholder('0%')
                            ->color(fn ($state) => match (true) {
                                $state < 20 => 'danger',
                                $state < 50 => 'warning',
                                default => 'success',
                            })
                            ->weight(FontWeight::Bold),

                        IconEntry::make('ignition_on')
                            ->label('Ignição')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger')
                            ->size(IconSize::Large),

                        IconEntry::make('relay_enabled')
                            ->label('Relé')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger')
                            ->size(IconSize::Large),
                    ]),

                // Location Section
                Section::make('Localização')
                    ->icon('heroicon-o-map-pin')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('last_latitude')
                            ->label('Latitude')
                            ->icon('heroicon-o-globe-alt')
                            ->placeholder('N/A')
                            ->copyable()
                            ->copyMessage('Latitude copiada!')
                            ->formatStateUsing(fn ($state) => $state ? number_format($state, 7) : '-'),

                        TextEntry::make('last_longitude')
                            ->label('Longitude')
                            ->icon('heroicon-o-globe-alt')
                            ->placeholder('N/A')
                            ->copyable()
                            ->copyMessage('Longitude copiada!')
                            ->formatStateUsing(fn ($state) => $state ? number_format($state, 7) : '-'),

                        TextEntry::make('last_update_at')
                            ->label('Última Atualização')
                            ->icon('heroicon-o-clock')
                            ->dateTime('d/m/Y H:i:s')
                            ->placeholder('Nunca atualizado')
                            ->since()
                            ->color('gray'),
                    ]),

                // Device Information
                Section::make('Dispositivo')
                    ->icon('heroicon-o-cpu-chip')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('device.serial')
                            ->label('Número de Série')
                            ->placeholder('Nenhum dispositivo')
                            ->icon('heroicon-o-hashtag')
                            ->copyable()
                            ->weight(FontWeight::Medium),

                        TextEntry::make('device.status')
                            ->label('Status do Dispositivo')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'online' => 'Online',
                                'offline' => 'Offline',
                                default => 'Desconhecido',
                            })
                            ->color(fn (?string $state): string => match ($state) {
                                'online' => 'success',
                                'offline' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn (?string $state): string => match ($state) {
                                'online' => 'heroicon-o-signal',
                                'offline' => 'heroicon-o-signal-slash',
                                default => 'heroicon-o-question-mark-circle',
                            }),
                    ])
                    ->collapsible(),

                // Timestamps Section
                Section::make('Registro')
                    ->icon('heroicon-o-calendar-days')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Criado em')
                            ->dateTime('d/m/Y H:i:s')
                            ->since()
                            ->color('gray'),

                        TextEntry::make('updated_at')
                            ->label('Atualizado em')
                            ->dateTime('d/m/Y H:i:s')
                            ->since()
                            ->color('gray'),
                    ])
                    ->collapsed(),
            ]);
    }
}
