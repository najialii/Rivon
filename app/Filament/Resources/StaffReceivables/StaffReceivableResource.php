<?php

namespace App\Filament\Resources\StaffReceivables;

use App\Filament\Resources\StaffReceivables\Pages\CreateStaffReceivable;
use App\Filament\Resources\StaffReceivables\Pages\EditStaffReceivable;
use App\Filament\Resources\StaffReceivables\Pages\ListStaffReceivables;
use App\Filament\Resources\StaffReceivables\Schemas\StaffReceivableForm;
use App\Filament\Resources\StaffReceivables\Tables\StaffReceivablesTable;
use App\Models\Salary;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class StaffReceivableResource extends Resource
{
    protected static ?string $model = Salary::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static string|UnitEnum|null $navigationGroup = 'Financials';
    protected static ?string $navigationLabel = 'Staff Receivables';
    protected static ?string $modelLabel = 'Staff Receivable';
    protected static ?string $pluralModelLabel = 'Staff Receivables';
    protected static ?string $slug = 'staff-receivables';

    /**
     * Scope the resource to only show receivable/advance/collection records,
     * keeping the original Salaries resource unaffected.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('transaction_type', ['receivable', 'advance', 'collection']);
    }

    public static function form(Schema $schema): Schema
    {
        return StaffReceivableForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffReceivablesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListStaffReceivables::route('/'),
            'create' => CreateStaffReceivable::route('/create'),
            'edit'   => EditStaffReceivable::route('/{record}/edit'),
        ];
    }
}
