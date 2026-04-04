<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('customer.name_ar')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('order_id')
                    ->label('Order #')
                    ->formatStateUsing(fn ($state) => $state ? "#{$state}" : '-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('issue_date')
                    ->label('Issue Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'primary' => 'sent',
                        'success' => 'paid',
                        'warning' => 'overdue',
                        'danger' => 'cancelled',
                    ]),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('usd', true)
                    ->sortable()
                    ->alignCenter()
                    ->weight('bold'),

                TextColumn::make('currency')
                    ->label('Currency')
                    ->sortable()
                    ->alignCenter(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('customer')
                    ->relationship('customer', 'name_en')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('currency')
                    ->options([
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'GBP' => 'GBP',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn ($record) => $record->status !== 'paid'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make()
                        // ->visible(fn () => auth()->user()->can('delete', \App\Models\Invoice::class)),
                ]),
            ]);
    }
}
