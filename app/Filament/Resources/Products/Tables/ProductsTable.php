<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use App\Models\Category;
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

                TextColumn::make('category.id')
                    ->label('Category')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('munit')
                    ->label('Unit')
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
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}