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
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name_en')
                            ->searchable()
                            ->preload()
                            ->required()->columnSpan(2),

                        Select::make('cost_id')
                            ->label('Associated Cost')
                            ->relationship('cost', 'name_en')
                            ->searchable()
                            ->preload()
                            ->required()->columnSpan(2),

                        TextInput::make('recived_qty')
                            ->label('Received Quantity')
                            ->numeric()
                            ->required()->columnSpan(2),

                        TextInput::make('origin_type')
                            ->label('Source / Origin')
                            ->placeholder('e.g., Local Factory, Import')->columnSpan(2),

                        DatePicker::make('recived_date')
                            ->label('Arrival Date')
                            ->default(now())
                            ->required()->columnSpan(2),

                        DatePicker::make('expiry_date')
                            ->label('Expiry Date')->columnSpan(2),
                    ])
            ]);
    }
}