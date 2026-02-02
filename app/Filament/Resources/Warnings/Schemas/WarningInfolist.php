<?php

namespace App\Filament\Resources\Warnings\Schemas;

use App\Models\Vehicle;
use App\Models\Warning;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Layout\Split;


class WarningInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Group::make([


                    Section::make()
                        ->schema([
                            Grid::make(3)
                                ->schema([
                                    TextEntry::make('type')
                                        ->label('Tipo de Aviso')
                                        ->formatStateUsing(fn($record) => $record->getTypeLabel())
                                        ->badge()
                                        ->size(TextSize::Large)
                                        ->weight(FontWeight::Bold)
                                        ->color(fn(string $state): string => match ($state) {
                                            'route_diversion' => 'warning',
                                            'cargo_theft' => 'danger',
                                            'fuel_theft' => 'danger',
                                            default => 'gray',
                                        }),

                                    TextEntry::make('severity')
                                        ->label('Gravidade')
                                        ->formatStateUsing(fn($record) => $record->getSeverityLabel())
                                        ->badge()
                                        ->size(TextSize::Large)
                                        ->weight(FontWeight::Bold)
                                        ->color(fn(string $state): string => match ($state) {
                                            'low' => 'success',
                                            'medium' => 'warning',
                                            'high' => 'danger',
                                            default => 'gray',
                                        }),

                                    TextEntry::make('occurred_at')
                                        ->label('Data e Hora')
                                        ->dateTime('d/m/Y • H:i')
                                        ->color('gray')
                                        ->helperText(fn($record) => $record->occurred_at->diffForHumans()),


                                    Section::make('Veículo')
                                        ->schema([
                                            TextEntry::make('vehicle.plate')
                                                ->label('Placa')
                                                ->weight(FontWeight::Bold)
                                                ->size(TextSize::Large)
                                                ->placeholder('Não atribuído')
                                                ->color('primary')
                                                ->url(fn($record) => $record->vehicle ? route('filament.dash.resources.vehicles.view', $record->vehicle) : null),

                                            TextEntry::make('vehicle.model')
                                                ->label('Modelo')
                                                ->weight(FontWeight::Medium)
                                                ->size(TextSize::Small)
                                                ->color('gray')
                                                ->placeholder('—'),
                                        ])->columnSpanFull()->columns(2)
                                        ->compact(),

                                    TextEntry::make('description')
                                        ->label('Descrição')
                                        ->prose()
                                        ->placeholder('Nenhuma descrição fornecida')

                                        ->live()
                                        ->columnSpanFull()
                                        ->color('gray')
                                        ->suffixAction(
                                            Action::make('edit')
                                                ->label('Editar')
                                                ->color('primary')
                                                ->icon(Heroicon::PencilSquare)
                                                ->iconSize('md')
                                                ->extraAttributes(['class' => 'pb-6'])
                                                ->schema([
                                                    Textarea::make('description')
                                                        ->label('Descrição')

                                                        ->rows(5)->live()
                                                        ->required(),
                                                ])
                                                ->action(function (array $data, $record) {
                                                    Warning::whereId($record->id)->update(['description' => $data['description']]);

                                                    $record->update($data);
                                                    \Filament\Notifications\Notification::make()
                                                        ->success()
                                                        ->title('Descrição atualizada!')
                                                        ->send();
                                                })
                                        ),
                                ]),
                        ]),

                    ViewEntry::make('route_map')

                        ->label('')
                        ->view('filament.widgets.embed-route-widget'),
                ])->columns(2)->columnSpanFull(),

                Grid::make(3)
                    ->schema([
                        // Main Content Column (2/3 width)
                        Group::make([


                            Section::make('Localização do Incidente')
                                ->schema([
                                    TextEntry::make('location')
                                        ->label('Localização')
                                        ->columnSpanFull()
                                        ->size(TextSize::Medium)
                                        ->weight(FontWeight::SemiBold)
                                        ->copyable()
                                        ->copyMessage('✓ Endereço copiado!')
                                        ->copyMessageDuration(1500)
                                        ->placeholder('Localização não informada')
                                        ->live()
                                        ->color('gray')
                                        ->suffixAction(
                                            Action::make('edit')
                                                ->label('Editar')
                                                ->color('primary')
                                                ->icon(Heroicon::PencilSquare)
                                                ->iconSize('md')
                                                ->extraAttributes(['class' => 'pb-6'])
                                                ->schema([
                                                    TextInput::make('location')
                                                        ->label('Localização')
                                                        ->live()
                                                        ->required(),
                                                ])
                                                ->action(function (array $data, $record) {
                                                    Warning::whereId($record->id)->update(['location' => $data['location']]);

                                                    $record->update($data);
                                                    \Filament\Notifications\Notification::make()
                                                        ->success()
                                                        ->title('Descrição atualizada!')
                                                        ->send();
                                                })
                                        ),

                                    Grid::make(2)
                                        ->schema([
                                            TextEntry::make('latitude')
                                                ->label('Coordenadas • Latitude')
                                                ->numeric(decimalPlaces: 7)
                                                ->copyable()
                                                ->copyMessage('✓ Copiado!')
                                                ->placeholder('—')
                                                ->color('gray')
                                                ->size(TextSize::Small),

                                            TextEntry::make('longitude')
                                                ->label('Coordenadas • Longitude')
                                                ->numeric(decimalPlaces: 7)
                                                ->copyable()
                                                ->copyMessage('✓ Copiado!')
                                                ->placeholder('—')
                                                ->color('gray')
                                                ->size(TextSize::Small),
                                        ]),
                                ])
                                ->collapsible()
                                ->collapsed(fn($record) => !$record->latitude && !$record->longitude)
                                ->compact(),

                            Section::make('Resolução')
                                ->description('Informações sobre o fechamento do incidente')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextEntry::make('resolved_at')
                                                ->label('Data de Resolução')
                                                ->dateTime('d/m/Y às H:i')
                                                ->badge()
                                                ->size(TextSize::Medium)
                                                ->color('success')
                                                ->weight(FontWeight::SemiBold)
                                                ->placeholder('⏳ Aguardando resolução')
                                                ->default('⏳ Aguardando resolução'),

                                            TextEntry::make('resolver.name')
                                                ->label('Responsável pela Resolução')
                                                ->weight(FontWeight::SemiBold)
                                                ->size(TextSize::Medium)
                                                ->color('gray')
                                                ->placeholder('—'),
                                        ]),

                                    TextEntry::make('resolution_notes')
                                        ->label('Observações')
                                        ->columnSpanFull()
                                        ->prose()
                                        ->placeholder('Nenhuma observação registrada')
                                        ->markdown()
                                        ->color('gray'),
                                ])
                                ->visible(fn($record) => $record->isResolved())
                                ->compact(),
                        ])->columnSpan(2),

                        // Sidebar Column (1/3 width)
                        Group::make([
                            Section::make('Motorista')
                                ->schema([
                                    Group::make([
                                        ImageEntry::make('driver.avatar_url')->hiddenLabel()->circular()->imageSize('64px'),
                                        TextEntry::make('driver.name')
                                            ->hiddenLabel()->columnSpan(3)
                                            ->weight(FontWeight::Bold)
                                            ->size(TextSize::Large)
                                            ->placeholder('Não atribuído')
                                            ->color('primary')
                                            ->helperText(fn($record) => preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', preg_replace('/\D/', '', $record->driver?->cpf ?? '')))
                                            ->extraAttributes(['class' => 'pt-2']),
                                    ])->columns(4),

                                    TextEntry::make('driver.phone')
                                        ->label('Contato')
                                        ->weight(FontWeight::Medium)
                                        ->size(TextSize::Small)
                                        ->color('gray')
                                        ->placeholder('—')
                                        ->formatStateUsing(fn($state) => preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $state)))
                                        ->copyable()
                                        ->copyMessage('✓ Copiado!'),
                                ])
                                ->compact(),

                        ])->columnSpan(1),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
