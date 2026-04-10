<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('otype')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sales' => 'success',
                        'purchase' => 'warning',
                        'quote' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                // Show Customer or Supplier depending on which is set
                TextColumn::make('entity_name')
                    ->label('Entity')
                    ->getStateUsing(fn (Order $record) => $record->customer?->name ?? $record->supplier?->name ?? 'N/A')
                    ->searchable(),

                // Since one order has many items, we show the count of items
                TextColumn::make('order_items_count')
                    ->label('Items')
                    ->counts('order_items')
                    ->alignCenter(),

                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('usd')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('usd')),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'confirmed' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                IconColumn::make('hasInvoice')
                    ->label('Invoiced')
                    ->boolean()
                    ->getStateUsing(fn (Order $record): bool => $record->hasInvoice())
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                TextColumn::make('order_date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('otype')
                    ->options([
                        'sales' => 'Sales',
                        'purchase' => 'Purchase',
                        'quote' => 'Quote',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                
                // Example: Re-enabling the Invoice Action
                Action::make('convert_to_invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record): bool => $record->canBeConvertedToInvoice())
                    ->action(function (Order $record) {
                        // Assuming your Service is ready
                        // $invoice = \App\Services\InvoiceService::convertOrderToInvoice($record);
                        
                        Notification::make()
                            ->title('Order Invoiced')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}