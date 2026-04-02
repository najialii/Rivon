<?php

namespace App\Filament\Resources\JEntries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;


class JEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //

                TextColumn::make('created_at')->dateTime()->label('Date'),
            TextColumn::make('account.name_en')->label('Account')->searchable(),
            TextColumn::make('reference_type')
                ->label('Source')
                ->formatStateUsing(fn ($state) => class_basename($state)), // Shows "Supply" or "Order"
            TextColumn::make('description_en')->label('Description')->limit(30),
            TextColumn::make('debit')
                ->money(fn ($record) => $record->currancy)
                ->color('success'),
            TextColumn::make('credit')
                ->money(fn ($record) => $record->currancy)
                ->color('danger'),
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
