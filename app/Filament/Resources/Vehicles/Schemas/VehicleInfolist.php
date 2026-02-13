<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Filament\Actions\ViewLivestreamAction;
use App\Filament\Infolists\Components\VideoCarousel;
use App\Filament\Resources\Warnings\WarningResource;
use App\Models\DriverHistory;
use App\Services\RelayCommandService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VehicleInfolist
{

    protected static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

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
                            ]),
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



                    // ... inside your Infolist schema
                    VideoCarousel::make('media_carousel')
                        ->state(function ($record) {
                            $macAddress = $record->device?->mac_address;

                            // Clean: AA:BB:CC:DD:EE:FF -> aabbccddeeff
                            $directory = $macAddress ? str_replace(':', '', strtolower($macAddress)) : null;

                            if (!$directory) {
                                return ['videos' => [], 'audios' => []];
                            }

                            $videoDisk = Storage::disk('minio');
                            $audioDisk = Storage::disk('minio_audio');

                            return Cache::remember("vehicle_media_{$record->id}_{$directory}", 300, function () use ($record, $directory, $videoDisk, $audioDisk) {

                                // 1. Pre-fetch Driver History to avoid N+1 queries loop
                                // We load only necessary fields and eager load the driver name
                                $driverHistory = DriverHistory::query()
                                    ->where('vehicle_id', $record->id)
                                    ->with('driver:id,name')
                                    ->orderBy('started_at')
                                    ->get();

                                // Helper to find driver in memory
                                $getDriverAtTimestamp = function (int $timestamp) use ($driverHistory) {
                                    $fileTime = Carbon::createFromTimestamp($timestamp);

                                    $history = $driverHistory->first(function ($log) use ($fileTime) {
                                        return $log->started_at <= $fileTime &&
                                            ($log->ended_at === null || $log->ended_at > $fileTime);
                                    });

                                    return $history?->driver?->name ?? 'Desconhecido';
                                };

                                // ==================== VIDEOS ====================
                                $videoFiles = $videoDisk->allFiles($directory);

                                $videos = collect($videoFiles)
                                    ->filter(function ($path) {
                                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                        return in_array($ext, ['mp4', 'webm', 'mov', 'avi', 'mkv']) && !str_contains($path, '_thumb');
                                    })
                                    ->map(function ($path) use ($videoDisk, $record, $getDriverAtTimestamp) {
                                        $filename = basename($path);

                                        // Parse timestamp
                                        $timestamp = preg_match('/vid_(\d+)/', $filename, $matches)
                                            ? (int)$matches[1]
                                            : time();

                                        // Resolve Driver based on History
                                        $driverName = $getDriverAtTimestamp($timestamp);

                                        // Generate signed URLs
                                        try {
                                            $videoUrl = $videoDisk->temporaryUrl($path, now()->addMinutes(60));
                                        } catch (\Exception $e) {
                                            $videoUrl = $videoDisk->url($path);
                                        }

                                        $thumbPath = preg_replace('/\.(mp4|webm|mov|avi|mkv)$/i', '_thumb.jpg', $path);

                                        try {
                                            $thumbnailUrl = $videoDisk->exists($thumbPath)
                                                ? $videoDisk->temporaryUrl($thumbPath, now()->addMinutes(60))
                                                : null;
                                        } catch (\Exception $e) {
                                            $thumbnailUrl = null;
                                        }

                                        // Get file size
                                        try {
                                            $size = $videoDisk->size($path);
                                            $sizeHuman = $size ? self::formatBytes($size) : '---';
                                        } catch (\Exception $e) {
                                            $sizeHuman = '---';
                                        }

                                        return [
                                            'id' => md5($path),
                                            'type' => 'video',
                                            'title' => $filename,
                                            'url' => $videoUrl,
                                            'thumbnail_url' => $thumbnailUrl,
                                            'duration' => 'N/A',
                                            'size' => $sizeHuman,
                                            'date' => \Carbon\Carbon::createFromTimestamp($timestamp)->format('d/m/Y H:i'),
                                            'driver' => $driverName, // <--- Now using history
                                            'vehicle' => $record->plate ?? 'Desconhecido',
                                            'status' => 'ready',
                                        ];
                                    })
                                    ->sortByDesc('date')
                                    ->values()
                                    ->toArray();

                                // ==================== AUDIOS ====================
                                $audioFiles = $audioDisk->allFiles($directory);

                                $audios = collect($audioFiles)
                                    ->filter(function ($path) {
                                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                        return in_array($ext, ['mp3', 'wav', 'ogg', 'm4a', 'aac']);
                                    })
                                    ->map(function ($path) use ($audioDisk, $record, $getDriverAtTimestamp) {
                                        $filename = basename($path);

                                        // Parse timestamp
                                        $timestamp = preg_match('/aud_(\d+)/', $filename, $matches)
                                            ? (int)$matches[1]
                                            : (preg_match('/_(\d{10})/', $filename, $matches2)
                                                ? (int)$matches2[1]
                                                : time());

                                        // Resolve Driver based on History
                                        $driverName = $getDriverAtTimestamp($timestamp);

                                        // Generate signed URL
                                        try {
                                            $audioUrl = $audioDisk->temporaryUrl($path, now()->addMinutes(60));
                                        } catch (\Exception $e) {
                                            $audioUrl = $audioDisk->url($path);
                                        }

                                        // Get file size
                                        try {
                                            $size = $audioDisk->size($path);
                                            $sizeHuman = $size ? self::formatBytes($size) : '---';
                                        } catch (\Exception $e) {
                                            $sizeHuman = '---';
                                        }

                                        return [
                                            'id' => md5($path),
                                            'type' => 'audio',
                                            'title' => $filename,
                                            'url' => $audioUrl,
                                            'thumbnail_url' => null,
                                            'duration' => 'N/A',
                                            'size' => $sizeHuman,
                                            'date' => \Carbon\Carbon::createFromTimestamp($timestamp)->format('d/m/Y H:i'),
                                            'driver' => $driverName, // <--- Now using history
                                            'vehicle' => $record->plate ?? 'Desconhecido',
                                            'status' => 'ready',
                                        ];
                                    })
                                    ->sortByDesc('date')
                                    ->values()
                                    ->toArray();

                                return [
                                    'videos' => $videos,
                                    'audios' => $audios,
                                ];
                            });
                        })
                        ->columnSpanFull()

                ])->columnSpanFull(),



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
                            ->color('gray'),
                    ])
                        ->collapsible()->columnSpan(4),

                    // Device Information
                    Section::make('Dispositivo')
                        ->schema([
                            TextEntry::make('device.mac_address')
                                ->label('Endereço MAC')
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
                        ])->columnSpan(1),
                ])->columns(5)->columnSpanFull(),

            ]);
    }
}
