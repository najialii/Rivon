<?php

namespace App\Filament\Resources\Supplies\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;

class SuppliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name_en')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('recived_qty')
                    ->label('Qty')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('cost.name_en')
                    ->label('Cost Profile')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('origin_type')
                    ->label('Origin')
                    ->toggleable(),

                TextColumn::make('recived_date')
                    ->label('Date')
                    ->date()
                    ->sortable()
                    ->color('primary'), // Your Aqua Deep

                TextColumn::make('expiry_date')
                    ->label('Expiry')
                    ->date()
                    ->color('danger')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('product_id')
                    ->relationship('product', 'name_en')
                    ->label('Filter by Product'),
                
                SelectFilter::make('cost_id')
                    ->relationship('cost', 'name_en')
                    ->label('Filter by Cost Type'),
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