<?php

namespace App\Filament\Resources\Stockmovments;

use App\Filament\Resources\Stockmovments\Pages\CreateStockmovment;
use App\Filament\Resources\Stockmovments\Pages\EditStockmovment;
use App\Filament\Resources\Stockmovments\Pages\ListStockmovments;
use App\Filament\Resources\Stockmovments\Schemas\StockmovmentForm;
use App\Filament\Resources\Stockmovments\Tables\StockmovmentsTable;
use App\Models\StockMov;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockmovmentResource extends Resource
{
    protected static ?string $model = StockMov::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StockmovmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockmovmentsTable::configure($table);
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
            'index' => ListStockmovments::route('/'),
            'create' => CreateStockmovment::route('/create'),
            'edit' => EditStockmovment::route('/{record}/edit'),
        ];
    }
}
