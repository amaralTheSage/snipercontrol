<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VehicleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('plate'),
                TextEntry::make('model'),
                TextEntry::make('year')
                    ->numeric(),
                TextEntry::make('type'),
                TextEntry::make('status'),
                TextEntry::make('current_driver_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('current_speed')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('fuel_level')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('ignition_on')
                    ->boolean(),
                IconEntry::make('relay_enabled')
                    ->boolean(),
                TextEntry::make('last_latitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('last_longitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('last_update_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
