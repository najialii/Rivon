<?php

namespace App\Filament\Resources\Supplies\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;


class SupplyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Supply Entry')
                    ->description('Log incoming stock and associated costs')
                    ->columns(2)
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name_en')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('cost_id')
                            ->label('Associated Cost')
                            ->relationship('cost', 'name_en')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('recived_qty')
                            ->label('Received Quantity')
                            ->numeric()
                            ->required(),

                        TextInput::make('origin_type')
                            ->label('Source / Origin')
                            ->placeholder('e.g., Local Factory, Import'),

                        DatePicker::make('recived_date')
                            ->label('Arrival Date')
                            ->default(now())
                            ->required(),

                        DatePicker::make('expiry_date')
                            ->label('Expiry Date'),
                    ])
            ]);
    }
}