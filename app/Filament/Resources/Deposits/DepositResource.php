<?php

namespace App\Filament\Resources\Deposits;

use App\Filament\Resources\Deposits\Pages\CreateDeposit;
use App\Filament\Resources\Deposits\Pages\EditDeposit;
use App\Filament\Resources\Deposits\Pages\ListDeposits;
use App\Filament\Resources\Deposits\Schemas\DepositForm;
use App\Filament\Resources\Deposits\Tables\DepositsTable;
use App\Models\Payment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DepositResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownTray;
    protected static string|UnitEnum|null $navigationGroup = 'Financials';
    protected static ?string $navigationLabel = 'Deposits';
    protected static ?string $modelLabel = 'Deposit';
    protected static ?string $pluralModelLabel = 'Deposits';
    protected static ?string $slug = 'deposits';

    /**
     * Scope the resource to only show deposit records,
     * keeping the original Payments resource unaffected.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('transaction_type', 'deposit');
    }

    public static function form(Schema $schema): Schema
    {
        return DepositForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepositsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDeposits::route('/'),
            'create' => CreateDeposit::route('/create'),
            'edit'   => EditDeposit::route('/{record}/edit'),
        ];
    }
}
