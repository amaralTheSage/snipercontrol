<?php

namespace App\Filament\Resources\Warnings;

use App\Filament\Resources\Warnings\Pages\CreateWarning;
use App\Filament\Resources\Warnings\Pages\EditWarning;
use App\Filament\Resources\Warnings\Pages\ListWarnings;
use App\Filament\Resources\Warnings\Pages\ViewWarning;
use App\Filament\Resources\Warnings\Schemas\WarningForm;
use App\Filament\Resources\Warnings\Schemas\WarningInfolist;
use App\Filament\Resources\Warnings\Tables\WarningsTable;
use App\Models\Warning;
use BackedEnum;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WarningResource extends Resource
{
    protected static ?string $model = Warning::class;

    protected static ?string $modelLabel = "Aviso";

    protected static ?int $navigationSort = 7;

    protected static string|BackedEnum|null $navigationIcon = LucideIcon::TriangleAlert;

    public static function form(Schema $schema): Schema
    {
        return WarningForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WarningInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WarningsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWarnings::route('/'),
            'create' => CreateWarning::route('/create'),
            'view' => ViewWarning::route('/{record}'),
            'edit' => EditWarning::route('/{record}/edit'),
        ];
    }
}
