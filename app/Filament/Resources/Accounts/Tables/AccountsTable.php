<?php

namespace App\Filament\Resources\Accounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
           ->columns([
            TextColumn::make('name_en')->label('Account Name'),
            TextColumn::make('account_type')
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'asset' => 'success',
                    'liability' => 'danger',
                    'revenue' => 'info',
                    'expense' => 'warning',
                    default => 'gray',
                }),
            TextColumn::make('balance') // Uses the getBalanceAttribute we made
                ->label('Live Balance')
                ->money(fn ($record) => $record->currancy)
                ->weight('bold'),
        ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
