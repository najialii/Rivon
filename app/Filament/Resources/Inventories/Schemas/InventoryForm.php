<?php

namespace App\Filament\Resources\Inventories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;

class InventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Stock Levels')
                    ->description('Manage current product availability and locations')
                    ->columns(2)
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name_en')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('total_qty')
                            ->label('Total Stock')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        TextInput::make('location')
                            ->label('Warehouse Location')
                            ->placeholder('e.g., A-1, Shelf 4')
                            ->maxLength(255),

                        TextInput::make('wholesale_recived_qty')
                            ->label('Wholesale Received')
                            ->numeric()
                            ->default(0),

                        TextInput::make('retail_recived_qty')
                            ->label('Retail Received')
                            ->numeric()
                            ->default(0),
                    ])
            ]);
    }
}