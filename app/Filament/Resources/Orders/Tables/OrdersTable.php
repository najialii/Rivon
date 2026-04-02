<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use App\Models\Category;
use Filament\Actions\DeleteBulkAction;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer_name')
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
                    ->money('usd', true)
                    ->sortable()
                    ->alignCenter(),

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
