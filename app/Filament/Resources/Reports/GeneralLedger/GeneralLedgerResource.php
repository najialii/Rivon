<?php

namespace App\Filament\Resources\Reports\GeneralLedger;

use App\Filament\Resources\Reports\GeneralLedger\Pages\ListGeneralLedger;
use App\Models\Jentry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class GeneralLedgerResource extends Resource
{
    protected static ?string $model = Jentry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static string|UnitEnum|null $navigationGroup = 'Financials';
    protected static ?string $navigationLabel = 'General Ledger';
    protected static ?string $slug = 'general-ledger';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->leftJoin('journal_transactions', 'journal_transactions.id', '=', 'jentries.journal_transaction_id')
            ->select('jentries.*')
            ->addSelect([
                'journal_entry_number' => 'journal_transactions.entry_number',
                'journal_entry_date' => 'journal_transactions.entry_date',
                'journal_memo_en' => 'journal_transactions.memo_en',
            ]);
    }

    public static function table(Table $table): Table
    {
        return Tables\GeneralLedgerTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            // 'index' => ListGeneralLedger::route('/'),
        ];
    }
}

