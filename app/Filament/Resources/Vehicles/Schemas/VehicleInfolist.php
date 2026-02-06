<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Filament\Actions\ViewLivestreamAction;
use App\Filament\Infolists\Components\VideoCarousel;
use App\Filament\Resources\Warnings\WarningResource;
use App\Filament\Widgets\RouteWidget;
use Filament\Actions\Action;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
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

                Group::make([

                    Group::make([

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
                                    ->formatStateUsing(fn(string $state): string => match ($state) {
                                        'truck' => 'Caminhão',
                                        'van' => 'Van',
                                        'car' => 'Carro',
                                        'pickup' => 'Pickup',
                                        default => $state,
                                    })
                                    ->color(fn(string $state): string => match ($state) {
                                        'truck' => 'info',
                                        'van' => 'success',
                                        'car' => 'warning',
                                        'pickup' => 'danger',
                                        default => 'gray',
                                    })
                                    ->icon(fn(string $state): string => match ($state) {
                                        'truck' => 'heroicon-o-truck',
                                        'van' => 'heroicon-o-building-office',
                                        'car' => 'heroicon-m-home',
                                        'pickup' => 'heroicon-o-cube-transparent',
                                        default => 'heroicon-o-question-mark-circle',
                                    }),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): string => match ($state) {
                                        'active' => 'Ativo',
                                        'maintenance' => 'Manutenção',
                                        'blocked' => 'Bloqueado',
                                        default => $state,
                                    })
                                    ->color(fn(string $state): string => match ($state) {
                                        'active' => 'success',
                                        'maintenance' => 'warning',
                                        'blocked' => 'danger',
                                        default => 'gray',
                                    })
                                    ->icon(fn(string $state): string => match ($state) {
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
                                    ->formatStateUsing(fn($state) => $state ? number_format($state, 7) : '-'),

                                TextEntry::make('last_longitude')
                                    ->label('Longitude')
                                    ->icon('heroicon-o-globe-alt')
                                    ->placeholder('N/A')
                                    ->copyable()
                                    ->copyMessage('Longitude copiada!')
                                    ->formatStateUsing(fn($state) => $state ? number_format($state, 7) : '-'),

                                TextEntry::make('last_update_at')
                                    ->label('Última Atualização')
                                    ->icon('heroicon-o-clock')
                                    ->dateTime('d/m/Y H:i:s')
                                    ->placeholder('Nunca atualizado')
                                    ->since()
                                    ->color('gray'),
                            ])
                    ]),

                    Group::make([
                        Section::make('Status Atual')
                            ->icon('heroicon-o-signal')
                            ->columns(3)
                            ->schema([
                                TextEntry::make('current_speed')
                                    ->label('Velocidade')
                                    ->suffix(' km/h')
                                    ->icon('heroicon-o-bolt')
                                    ->placeholder('0 km/h')
                                    ->color(fn($state) => match (true) {
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
                                    ->color(fn($state) => match (true) {
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


                            ]),

                    ])->columns(2)->columnSpanFull(),


                    VideoCarousel::make('videos')
                        ->state(function ($record) {
                            return $record->videoRecordings()
                                ->with(['driver', 'vehicle'])
                                ->latest()
                                ->get()
                                ->map(function ($video) {
                                    return [
                                        'id' => $video->id,
                                        'title' => $video->filename,
                                        'url' => route('videos.show', $video->id),
                                        'thumbnail_url' => $video->getThumbnailUrl(),
                                        'duration' => $video->duration_human,
                                        'size' => $video->file_size_human,
                                        'date' => $video->created_at->format('d/m/Y H:i'),
                                        'driver' => $video->driver?->name,
                                        'vehicle' => $video->vehicle?->plate,
                                        'status' => $video->status,

                                    ];
                                })
                                ->toArray();
                        }),
                ])->columnSpanFull(),

                #VehicleInfolist
                ViewLivestreamAction::make(),

                Group::make([

                    Section::make('Avisos (últimos 5)')->schema([

                        RepeatableEntry::make('warnings')
                            ->hiddenLabel()
                            ->state(fn($record) => $record->warnings()->latest('occurred_at')->limit(5)->get())
                            ->table([
                                TableColumn::make('Tipo'),
                                TableColumn::make('Severidade'),
                                TableColumn::make('Motorista'),

                                TableColumn::make('Status'),
                            ])->contained(false)->extraAttributes([
                                'class' => 'bg-card',
                            ])
                            ->schema([
                                TextEntry::make('type')->extraAttributes([
                                    'class' => 'bg-card',
                                ])
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'route_diversion' => 'warning',
                                        'cargo_theft' => 'danger',
                                        'fuel_theft' => 'danger',
                                        default => 'gray',
                                    })->extraEntryWrapperAttributes(['class' => 'repeater'])
                                    ->formatStateUsing(fn($record) => $record->getTypeLabel())
                                    ->url(fn($record) => WarningResource::getUrl('view', ['record' => $record]))
                                    ->openUrlInNewTab(false),

                                TextEntry::make('severity')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'low' => 'success',
                                        'medium' => 'warning',
                                        'high' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn($record) => $record->getSeverityLabel()),

                                TextEntry::make('driver.name')
                                    ->limit(50)
                                    ->placeholder('Sem descrição'),



                                TextEntry::make('resolved_at')
                                    ->badge()
                                    ->placeholder('Pendente')
                                    ->color(fn($state) => $state ? 'success' : 'danger')
                                    ->formatStateUsing(fn($state) => $state ? 'Resolvido' : 'Pendente'),
                            ])->extraAttributes([
                                'class' => 'custom-warning-table',
                            ]),
                    ])->headerActions([
                        Action::make('viewAllWarnings')
                            ->label('Ver Todos os Avisos')
                            ->url(function ($record) {

                                return WarningResource::getUrl('index', [
                                    'search' => $record->plate,
                                ]);
                            })
                            ->openUrlInNewTab(false)
                            ->color('gray')
                    ])
                        ->collapsible()->columnSpan(4),

                    // Device Information
                    Section::make('Dispositivo')
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
                                ->formatStateUsing(fn(?string $state): string => match ($state) {
                                    'online' => 'Online',
                                    'offline' => 'Offline',
                                    default => 'Desconhecido',
                                })
                                ->color(fn(?string $state): string => match ($state) {
                                    'online' => 'success',
                                    'offline' => 'danger',
                                    default => 'gray',
                                })
                                ->icon(fn(?string $state): string => match ($state) {
                                    'online' => 'heroicon-o-signal',
                                    'offline' => 'heroicon-o-signal-slash',
                                    default => 'heroicon-o-question-mark-circle',
                                }),
                        ])->columnSpan(1)
                ])->columns(5)->columnSpanFull(),


            ]);
    }
}
