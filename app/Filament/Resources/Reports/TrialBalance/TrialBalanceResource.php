<?php

namespace App\Filament\Resources\Reports\TrialBalance;

use App\Filament\Resources\Reports\TrialBalance\Pages\ListTrialBalances;
use App\Models\Account;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class TrialBalanceResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalculator;
    protected static string|UnitEnum|null $navigationGroup = 'Financials';
    protected static ?string $navigationLabel = 'Trial Balance';
    protected static ?string $slug = 'trial-balance';

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

    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()
    //         ->leftJoin('jentries', 'jentries.account_id', '=', 'accounts.id')
    //         ->leftJoin('journal_transactions', 'journal_transactions.id', '=', 'jentries.journal_transaction_id')
    //         ->select('accounts.*')
    //         ->selectRaw('COALESCE(SUM(CASE WHEN jentries.debit > 0 THEN jentries.debit ELSE 0 END), 0) AS period_debits')
    //         ->selectRaw('COALESCE(SUM(CASE WHEN jentries.credit > 0 THEN jentries.credit ELSE 0 END), 0) AS period_credits')
    //         ->groupBy('accounts.id');
            
    // }
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->leftJoin('jentries', 'jentries.account_id', '=', 'accounts.id')
        ->leftJoin('journal_transactions', 'journal_transactions.id', '=', 'jentries.journal_transaction_id')
        ->select(
            'accounts.id', 
            'accounts.parent_id', 
            'accounts.code', 
            'accounts.name_ar', 
            'accounts.name_en', 
            'accounts.account_type'
        )
        ->selectRaw('COALESCE(SUM(jentries.debit), 0) AS period_debits')
        ->selectRaw('COALESCE(SUM(jentries.credit), 0) AS period_credits')
        ->groupBy(
            'accounts.id', 
            'accounts.parent_id', 
            'accounts.code', 
            'accounts.name_ar', 
            'accounts.name_en', 
            'accounts.account_type'
        );
}


    public static function table(Table $table): Table
    {
        return Tables\TrialBalanceTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTrialBalances::route('/'),
        ];
    }
}
