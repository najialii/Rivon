<?php

namespace App\Filament\Resources\StaffReceivables\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StaffReceivablesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Staff Member | الموظف')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('transaction_type')
                    ->label('Type | النوع')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'receivable' => 'Receivable',
                        'advance'    => 'Advance',
                        'collection' => 'Collection',
                        default      => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'receivable' => 'danger',
                        'advance'    => 'warning',
                        'collection' => 'success',
                        default      => 'gray',
                    }),

                TextColumn::make('amount')
                    ->label('Amount | المبلغ')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('amount_settled')
                    ->label('Settled | المسوى')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->placeholder('0.00')
                    ->sortable(),

                TextColumn::make('outstanding_balance')
                    ->label('Outstanding | المتبقي')
                    ->getStateUsing(fn ($record) => $record->outstanding_balance)
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->color(fn ($record) => $record->outstanding_balance > 0 ? 'danger' : 'success')
                    ->weight('bold'),

                TextColumn::make('status')
                    ->label('Status | الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'partial'   => 'info',
                        'settled'   => 'success',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    }),

                TextColumn::make('due_date')
                    ->label('Due Date | الاستحقاق')
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => (
                        $record->due_date &&
                        $record->due_date->isPast() &&
                        in_array($record->status, ['pending', 'partial'])
                    ) ? 'danger' : null)
                    ->placeholder('—'),

                TextColumn::make('receivableAccount.name_en')
                    ->label('GL Account')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Recorded At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('transaction_type')
                    ->label('Type')
                    ->options([
                        'receivable' => 'Receivable',
                        'advance'    => 'Advance',
                        'collection' => 'Collection',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pending',
                        'partial'   => 'Partial',
                        'settled'   => 'Settled',
                        'cancelled' => 'Cancelled',
                    ]),
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
