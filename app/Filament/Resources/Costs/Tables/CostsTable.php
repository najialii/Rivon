<?php

namespace App\Filament\Resources\Costs\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class CostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_ar')
                    ->label('المصروف')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->extraAttributes(['dir' => 'rtl']),

                TextColumn::make('name_en')
                    ->label('Cost Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cost_price')
                    ->label('Amount')
                    ->money('USD') // Matches your currency
                    ->color('primary') // Your Aqua Deep
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('cost_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color('gray'),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('cost_type')
                    ->options([
                        'shipping' => 'Shipping',
                        'customs' => 'Customs',
                        'packaging' => 'Packaging',
                        'labor' => 'Labor',
                    ]),
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