<?php

namespace App\Filament\Resources\LoyaltyPoints;

use App\Filament\Resources\LoyaltyPoints\Pages\CreateLoyaltyPoints;
use App\Filament\Resources\LoyaltyPoints\Pages\EditLoyaltyPoints;
use App\Filament\Resources\LoyaltyPoints\Pages\ListLoyaltyPoints;
use App\Filament\Resources\LoyaltyPoints\Schemas\LoyaltyPointsForm;
use App\Filament\Resources\LoyaltyPoints\Tables\LoyaltyPointsTable;
use App\Models\Loyalty_Points;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class LoyaltyPointsResource extends Resource
{
    protected static ?string $model = Loyalty_Points::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Star;
        protected static string | UnitEnum | null $navigationGroup = 'Sales';


    public static function form(Schema $schema): Schema
    {
        return LoyaltyPointsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoyaltyPointsTable::configure($table);
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
            'index' => ListLoyaltyPoints::route('/'),
            'create' => CreateLoyaltyPoints::route('/create'),
            'edit' => EditLoyaltyPoints::route('/{record}/edit'),
        ];
    }
}
