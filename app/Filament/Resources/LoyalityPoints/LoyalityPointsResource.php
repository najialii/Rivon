<?php

namespace App\Filament\Resources\LoyalityPoints;

use App\Filament\Resources\LoyalityPoints\Pages\CreateLoyalityPoints;
use App\Filament\Resources\LoyalityPoints\Pages\EditLoyalityPoints;
use App\Filament\Resources\LoyalityPoints\Pages\ListLoyalityPoints;
use App\Filament\Resources\LoyalityPoints\Schemas\LoyalityPointsForm;
use App\Filament\Resources\LoyalityPoints\Tables\LoyalityPointsTable;
use App\Models\Loyalitypt;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum; 
class LoyalityPointsResource extends Resource
{
    protected static ?string $model = Loyalitypt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Star;
    protected static string|UnitEnum|null $navigationGroup = 'Sales';
    protected static ?string $navigationLabel = 'loyality points';


    public static function form(Schema $schema): Schema
    {
        return LoyalityPointsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoyalityPointsTable::configure($table);
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
            'index' => ListLoyalityPoints::route('/'),
            'create' => CreateLoyalityPoints::route('/create'),
            'edit' => EditLoyalityPoints::route('/{record}/edit'),
        ];
    }
}
