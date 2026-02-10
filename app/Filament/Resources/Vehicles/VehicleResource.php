<?php

namespace App\Filament\Resources\Vehicles;

use App\Filament\Resources\Vehicles\Pages\CreateVehicle;
use App\Filament\Resources\Vehicles\Pages\EditVehicle;
use App\Filament\Resources\Vehicles\Pages\ListVehicles;
use App\Filament\Resources\Vehicles\Pages\ViewVehicle;
use App\Filament\Resources\Vehicles\RelationManagers\CurrentDriverRelationManager;
use App\Filament\Resources\Vehicles\RelationManagers\DeviceRelationManager;
use App\Filament\Resources\Vehicles\Schemas\VehicleForm;
use App\Filament\Resources\Vehicles\Schemas\VehicleInfolist;
use App\Filament\Resources\Vehicles\Tables\VehiclesTable;
use App\Models\Vehicle;
use BackedEnum;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static string|BackedEnum|null $navigationIcon = LucideIcon::Van;

    protected static ?string $modelLabel = 'Veículo';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return VehicleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VehicleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehiclesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('company_id', auth()->id());
    }

    public static function getRelations(): array
    {
        return [
            DeviceRelationManager::class,
            CurrentDriverRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVehicles::route('/'),
            'create' => CreateVehicle::route('/create'),
            'view' => ViewVehicle::route('/{record}'),
            'edit' => EditVehicle::route('/{record}/edit'),
        ];
    }
}
