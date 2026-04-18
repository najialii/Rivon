<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('expense_date', 'desc')
            ->columns([
                TextColumn::make('expense_date')
                    ->label('Date | التاريخ')
                    ->date()
                    ->sortable(),

                TextColumn::make('name_en')
                    ->label('Expense Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('name_ar')
                    ->label('الاسم')
                    ->searchable()
                    ->extraAttributes(['dir' => 'rtl'])
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('cost_type')
                    ->label('Category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'rent'          => 'Rent & Utilities',
                        'salaries'      => 'Salaries',
                        'shipping'      => 'Shipping',
                        'customs'       => 'Customs',
                        'packaging'     => 'Packaging',
                        'labor'         => 'Labor',
                        'raw_materials' => 'Raw Materials',
                        'manufacturing' => 'Manufacturing',
                        'storage'       => 'Storage',
                        'marketing'     => 'Marketing',
                        'travel'        => 'Travel',
                        'maintenance'   => 'Maintenance',
                        'office'        => 'Office',
                        default         => ucfirst($state),
                    })
                    ->color('info'),

                TextColumn::make('cost_price')
                    ->label('Amount')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->sortable()
                    ->color('danger')
                    ->weight('bold'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'paid'     => 'info',
                        'pending'  => 'warning',
                        'rejected' => 'danger',
                        default    => 'gray',
                    }),

                TextColumn::make('paidBy.name')
                    ->label('Paid By')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('expenseAccount.name_en')
                    ->label('GL Account')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Recorded At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('cost_type')
                    ->label('Category')
                    ->options([
                        'rent'          => 'Rent & Utilities',
                        'salaries'      => 'Salaries',
                        'shipping'      => 'Shipping',
                        'customs'       => 'Customs',
                        'packaging'     => 'Packaging',
                        'labor'         => 'Labor',
                        'raw_materials' => 'Raw Materials',
                        'manufacturing' => 'Manufacturing',
                        'storage'       => 'Storage',
                        'marketing'     => 'Marketing',
                        'travel'        => 'Travel',
                        'maintenance'   => 'Maintenance',
                        'office'        => 'Office',
                        'other'         => 'Other',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'paid'     => 'Paid',
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
