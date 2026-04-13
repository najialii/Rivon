<?php

namespace App\Filament\Resources\JEntries;

use App\Filament\Resources\JEntries\Pages\CreateJEntries;
use App\Filament\Resources\JEntries\Pages\EditJEntries;
use App\Filament\Resources\JEntries\Pages\ListJEntries;
use App\Filament\Resources\JEntries\Schemas\JEntriesForm;
use App\Filament\Resources\JEntries\Tables\JEntriesTable;
use App\Models\JournalTransaction;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class JEntriesResource extends Resource
{
    protected static ?string $model = JournalTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
            protected static string | UnitEnum | null $navigationGroup = 'Financials';

            protected static ?string $navigationLabel = 'Journal Entries';

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withSum('lines', 'debit')
            ->withSum('lines', 'credit')
            ->withCount('lines');
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
