<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order; // Added this import
use App\Services\InvoiceService;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Tables\Actions\Action;
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
                    ->label('Order ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer.name')
                    ->label('Customer Name')    
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.name_en')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('usd')
                    ->sortable()
                    ->alignCenter(),

                IconColumn::make('hasInvoice')
                    ->label('Invoiced')
                    ->boolean()
                    // Fixed: Using the imported 'Order' class instead of full path string
                    ->getStateUsing(fn (Order $record): bool => $record->hasInvoice())
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                // Action::make('convert_to_invoice')
                //     ->label('Convert to Invoice')
                //     ->icon('heroicon-o-document-text')
                //     ->color('primary')
                //     // Fixed: Type-hint correctly references root App\Models\Order
                //     ->visible(fn (Order $record): bool => $record->canBeConvertedToInvoice())
                //     ->action(function (Order $record) {
                //         $invoice = InvoiceService::convertOrderToInvoice($record);
                        
                //         Notification::make()
                //             ->title('Invoice Created Successfully')
                //             ->success()
                //             ->send();

                //         return redirect()->route('filament.admin.resources.invoices.edit', ['record' => $invoice->id]);
                //     }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}