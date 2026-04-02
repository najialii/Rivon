<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('img_path')
                    ->label('')
                    ->square()
                    ->visibility('public')
                    ->disk('public')
                    ->size(50),

                TextColumn::make('name_ar')
                    ->label('المنتج')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->extraAttributes(['dir' => 'rtl']),

                TextColumn::make('name_en')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable()
                    ->color('gray'),

                TextColumn::make('category.name_ar')
                    ->label('Category')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('brand.name_en')
                    ->label('Brand')
                    ->searchable()
                    ->sortable()
                    ->color('primary'),

                TextColumn::make('price.price')
                    ->label('Retail Price')
                    ->money(fn ($record) => $record->price->currency ?? 'USD') 
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),

                TextColumn::make('price.wholesale_price')
                    ->label('Wholesale')
                    ->money(fn ($record) => $record->price->currency ?? 'USD')
                    ->toggleable()
                    ->color('warning'),

                TextColumn::make('munit')
                    ->label('Unit')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => 'Inactive',
                        1 => 'Active',
                        2 => 'Archived',
                        default => 'Unknown',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        0 => 'danger',
                        1 => 'success',
                        2 => 'gray',
                        default => 'gray',
                    })
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name_ar')
                    ->label('Filter Category'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make(),

            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
