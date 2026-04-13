<?php

namespace App\Filament\Resources\JEntries\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\BadgeColumn;

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

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'posted',
                        'danger' => 'voided',
                    ]),

                TextColumn::make('lines_count')
                    ->label('Lines')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('lines_sum_debit')
                    ->label('Debit')
                    ->money(fn ($record) => $record->currency)
                    ->color('success')
                    ->alignEnd(),

                TextColumn::make('lines_sum_credit')
                    ->label('Credit')
                    ->money(fn ($record) => $record->currency)
                    ->color('danger')
                    ->alignEnd(),

                TextColumn::make('memo_en')
                    ->label('Memo')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: false),
                    
            ])
            ->filters([
                //
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
