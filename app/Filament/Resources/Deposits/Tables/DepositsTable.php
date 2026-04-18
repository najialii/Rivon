<?php

namespace App\Filament\Resources\Deposits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DepositsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('payment_date', 'desc')
            ->columns([
                TextColumn::make('payment_date')
                    ->label('Date | التاريخ')
                    ->date()
                    ->sortable(),

                TextColumn::make('source_type')
                    ->label('Source | المصدر')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'public' => 'Public',
                        'staff'  => 'Staff',
                        default  => 'Unknown',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'public' => 'info',
                        'staff'  => 'warning',
                        default  => 'gray',
                    }),

                TextColumn::make('depositedBy.name')
                    ->label('Staff Member | الموظف')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('customer.name')
                    ->label('Customer | العميل')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('amount')
                    ->label('Amount | المبلغ')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),

                TextColumn::make('method')
                    ->label('Method | الطريقة')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash'          => 'Cash',
                        'bank_transfer' => 'Bank Transfer',
                        'cheque'        => 'Cheque',
                        'mobile_money'  => 'Mobile Money',
                        'card'          => 'Card',
                        default         => ucfirst($state),
                    })
                    ->color('gray'),

                TextColumn::make('reference')
                    ->label('Reference | المرجع')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('depositAccount.name_en')
                    ->label('Credit Account | الحساب')
                    ->placeholder('Default Cash (1010)')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Recorded At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('source_type')
                    ->label('Source Type')
                    ->options([
                        'public' => 'Public',
                        'staff'  => 'Staff',
                    ]),

                SelectFilter::make('method')
                    ->label('Payment Method')
                    ->options([
                        'cash'          => 'Cash',
                        'bank_transfer' => 'Bank Transfer',
                        'cheque'        => 'Cheque',
                        'mobile_money'  => 'Mobile Money',
                        'card'          => 'Card',
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
