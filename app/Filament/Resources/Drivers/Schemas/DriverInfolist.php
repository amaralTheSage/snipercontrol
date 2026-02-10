<?php

namespace App\Filament\Resources\Drivers\Schemas;

use App\Filament\Widgets\RouteWidget;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class DriverInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Driver Profile Section

                // Livewire::make(RouteWidget::class, fn($record) => [
                //     'vehicleId' => $record->currentVehicle?->id,
                // ])
                //     ->columnSpanFull(),

                Section::make('Perfil do Motorista')
                    ->icon('heroicon-o-user-circle')
                    ->columns(4)
                    ->schema([
                        ImageEntry::make('avatar_url')
                            ->hiddenLabel()
                            ->circular()
                            ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->name))
                            ->columnSpan(1)
                            ->imageSize(90),

                        TextEntry::make('name')
                            ->label('Nome Completo')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->columnSpan(2),

                        TextEntry::make('status')
                            ->hiddenLabel()
                            ->badge()
                            ->size(TextSize::Large)
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'active' => 'Ativo',
                                'inactive' => 'Inativo',
                                default => $state,
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn (string $state): string => match ($state) {
                                'active' => 'heroicon-o-check-circle',
                                'inactive' => 'heroicon-o-x-circle',
                                default => 'heroicon-o-question-mark-circle',
                            }),

                        TextEntry::make('cpf')
                            ->label('CPF')
                            ->icon('heroicon-o-identification')
                            ->copyable()
                            ->copyMessage('CPF copiado!')
                            ->weight(FontWeight::Medium)
                            ->columnSpan(2)
                            ->formatStateUsing(
                                fn (string $state): string => preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $state)
                            ),

                        TextEntry::make('phone')
                            ->label('Telefone')
                            ->icon('heroicon-o-phone')
                            ->columnSpan(2)
                            ->placeholder('Não informado')
                            ->copyable()
                            ->copyMessage('Telefone copiado!')
                            ->url(fn ($state) => $state ? 'tel:'.$state : null)
                            ->formatStateUsing(
                                fn (?string $state): ?string => $state ? preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $state)) : null
                            ),

                    ]),

                // Current Vehicle Section
                Section::make('Veículo Atual')
                    ->icon('heroicon-o-truck')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('currentVehicle.plate')
                            ->label('Placa')
                            ->placeholder('Nenhum veículo')
                            ->badge()
                            ->color('primary')
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-hashtag'),

                        TextEntry::make('currentVehicle.model')
                            ->label('Modelo')
                            ->placeholder('-')
                            ->icon('heroicon-o-cube')
                            ->weight(FontWeight::Medium),

                        TextEntry::make('currentVehicle.type')
                            ->label('Tipo')
                            ->placeholder('-')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'truck' => 'Caminhão',
                                'van' => 'Van',
                                'car' => 'Carro',
                                'pickup' => 'Pickup',
                                null => '-',
                                default => $state,
                            })
                            ->color(fn (?string $state): string => match ($state) {
                                'truck' => 'info',
                                'van' => 'success',
                                'car' => 'warning',
                                'pickup' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('currentVehicle.status')
                            ->label('Status do Veículo')
                            ->placeholder('-')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'active' => 'Ativo',
                                'maintenance' => 'Manutenção',
                                'blocked' => 'Bloqueado',
                                null => '-',
                                default => $state,
                            })
                            ->color(fn (?string $state): string => match ($state) {
                                'active' => 'success',
                                'maintenance' => 'warning',
                                'blocked' => 'danger',
                                default => 'gray',
                            }),
                    ])
                    ->hidden(fn ($record) => ! $record->currentVehicle)
                    ->collapsible(),

                // Vehicle Details (if assigned)
                Section::make('Detalhes do Veículo')
                    ->icon('heroicon-o-information-circle')
                    ->columns(3)
                    ->schema([

                        TextEntry::make('currentVehicle.fuel_level')
                            ->label('Combustível')
                            ->suffix('%')
                            ->placeholder('0%')
                            ->color(fn ($state) => match (true) {
                                $state < 20 => 'danger',
                                $state < 50 => 'warning',
                                default => 'success',
                            })
                            ->weight(FontWeight::Bold),

                        TextEntry::make('currentVehicle.ignition_on')
                            ->label('Ignição')
                            ->badge()
                            ->formatStateUsing(fn (?bool $state): string => match ($state) {
                                true => 'Ligada',
                                false => 'Desligada',
                                null => '-',
                            })
                            ->color(fn (?bool $state): string => match ($state) {
                                true => 'success',
                                false => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn (?bool $state): string => match ($state) {
                                true => 'heroicon-o-check-circle',
                                false => 'heroicon-o-x-circle',
                                default => 'heroicon-o-question-mark-circle',
                            }),

                        TextEntry::make('currentVehicle.device.serial')
                            ->label('Dispositivo')
                            ->placeholder('Nenhum dispositivo')
                            ->copyable()
                            ->weight(FontWeight::Medium),

                        TextEntry::make('currentVehicle.device.status')
                            ->label('Status do Dispositivo')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'online' => 'Online',
                                'offline' => 'Offline',
                                null => '-',
                                default => $state,
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

                        TextEntry::make('currentVehicle.last_update_at')
                            ->label('Última Atualização')
                            ->icon('heroicon-o-clock')
                            ->dateTime('d/m/Y H:i:s')
                            ->placeholder('-')
                            ->since()
                            ->color('gray'),
                    ])
                    ->hidden(fn ($record) => ! $record->currentVehicle)
                    ->collapsed(),

            ]);
    }
}
