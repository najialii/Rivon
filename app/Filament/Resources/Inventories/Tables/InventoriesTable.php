<?php

namespace App\Filament\Resources\Inventories\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;

class InventoriesTable
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

                TextColumn::make('total_qty')
                    ->label('Total Stock')
                    ->badge()
                    ->color(fn ($state): string => $state <= 5 ? 'danger' : 'primary')
                    ->sortable(),

                TextColumn::make('location')
                    ->label('Location')
                    ->searchable()
                    ->color('gray'),

                TextColumn::make('wholesale_recived_qty')
                    ->label('Wholesale')
                    ->numeric()
                    ->toggleable(),

                TextColumn::make('retail_recived_qty')
                    ->label('Retail')
                    ->numeric()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Last Count')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('location')
                    ->options([
                        // Add static locations or pull from DB if needed
                    ])
                    ->label('Filter by Warehouse'),
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