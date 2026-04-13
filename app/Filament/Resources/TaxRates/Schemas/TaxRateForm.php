<?php

namespace App\Filament\Resources\TaxRates\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TaxRateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tax Rate')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('code')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('rate')
                            ->label('Rate (%)')
                            ->numeric()
                            ->step(0.0001)
                            ->required(),

                        Select::make('tax_account_id')
                            ->label('Tax Account')
                            ->relationship('taxAccount', 'name_en')
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }
}
