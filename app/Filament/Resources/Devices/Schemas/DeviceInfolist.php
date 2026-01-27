<?php

namespace App\Filament\Resources\Devices\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DeviceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('serial'),
                TextEntry::make('vehicle_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('last_communication_at')
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
