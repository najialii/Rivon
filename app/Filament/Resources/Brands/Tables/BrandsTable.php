<?php

namespace App\Filament\Resources\Brands\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\BulkActionGroup;
use App\Models\Category;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;

class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                ImageColumn::make('logo_path')
                    ->label('')
                    ->square()
                    ->visibility('public')
                    ->disk('public')
                    ->size(50),

                TextColumn::make('name_ar')
                    ->label('العلامة التجارية')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->extraAttributes(['dir' => 'rtl']),

                TextColumn::make('name_en')
                    ->label('Brand Name')
                    ->searchable()
                    ->sortable()
                    ->color('gray'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                    

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
