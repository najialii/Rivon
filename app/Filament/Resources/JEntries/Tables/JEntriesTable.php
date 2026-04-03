<?php

namespace App\Filament\Resources\JEntries\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class JEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('entry_number')
                    ->label('Entry #')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono'),

                TextColumn::make('entry_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('account.name_en')
                    ->label('Account')
                    ->searchable(),

                TextColumn::make('debit')
                    ->label('Debit')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->color('success')
                    ->alignEnd(),

                TextColumn::make('credit')
                    ->label('Credit')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->color('danger')
                    ->alignEnd(),

                TextColumn::make('reference_type')
                    ->label('Source')
                    ->badge()
                    ->color('gray'),
                    
            ])
            ->filters([
                // Add filters for Date and Account here
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    
}