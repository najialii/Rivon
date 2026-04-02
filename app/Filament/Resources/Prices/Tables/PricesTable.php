<?php

namespace App\Filament\Resources\Prices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PricesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name_en')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Price')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),

                TextColumn::make('wholesale_price')
                    ->label('wholesale Price')
                    ->money(fn ($record) => $record->currency ?? 'USD')     ->color('success')
                    ->weight('bold'),

                    TextColumn::make('retail_price')
                    ->label('Retail Price')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->toggleable(isToggledHiddenByDefault: true)           ->color('success')
                    ->weight('bold'),

                    TextColumn::make('wholesale_min_price')
                    ->label('Wholesale Minimum Price')
                    ->toggleable(isToggledHiddenByDefault: true)           ->color('success')
                    ->weight('bold'),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),


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