<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('img_path')
                    ->label('')
                    ->square()
                    ->size(50),

                TextColumn::make('name_en')
                    ->label('Product Name')
                    ->description(fn ($record) => $record->name_ar) // Shows Arabic under English
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(),

                // --- FIXED UNIT COLUMNS ---
                TextColumn::make('measurement_unit')
                    ->label('Unit Type')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('unit_quantity')
                    ->label('Qty/Unit')
                    ->suffix(' pcs') // Matching your form's suffix
                    ->sortable(),

                TextColumn::make('unit_weight')
                    ->label('Weight')
                    ->suffix(' kg')
                    ->toggleable(),
                // --------------------------

                TextColumn::make('retail_price')
                    ->label('Retail Price')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'archived' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name_en')
                    ->label('Category'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}