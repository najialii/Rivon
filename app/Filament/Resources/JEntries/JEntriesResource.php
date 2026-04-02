<?php

namespace App\Filament\Resources\JEntries;

use App\Filament\Resources\JEntries\Pages\CreateJEntries;
use App\Filament\Resources\JEntries\Pages\EditJEntries;
use App\Filament\Resources\JEntries\Pages\ListJEntries;
use App\Filament\Resources\JEntries\Schemas\JEntriesForm;
use App\Filament\Resources\JEntries\Tables\JEntriesTable;
use App\Models\JEntries;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JEntriesResource extends Resource
{
    protected static ?string $model = JEntries::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return JEntriesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JEntriesTable::configure($table);
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
            'index' => ListJEntries::route('/'),
            'create' => CreateJEntries::route('/create'),
            'edit' => EditJEntries::route('/{record}/edit'),
        ];
    }
}
