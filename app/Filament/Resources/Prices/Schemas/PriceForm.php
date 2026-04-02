<?php

namespace App\Filament\Resources\Prices\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PriceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pricing Configuration')
                    ->description('Set product selling prices and margins')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name_en')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('price')
                            ->label('Original Price')
                            ->numeric()
                            ->prefix('$')->columnSpan(2)
                            ->required(),
                        TextInput::make('wholesale_price')
                            ->label('Wholesale Price')
                            ->numeric()->columnSpan(2)
                            ->prefix('$')
                            ->required(),

                        TextInput::make('retail_price')
                            ->label('Retail Price')
                            ->numeric()
                            ->prefix('$')->columnSpan(2)
                            ->required(),

                        TextInput::make('wholesale_min_price')
                            ->label('Wholesale Minimum Price')
                            ->numeric()
                            ->prefix('$')->columnSpan(2)
                            ->required(),

                        Select::make('currency')
                            ->label('Currency | العملة')
                            ->options([
                                'EGP' => 'EGP - Egyptian Pound | ج.م - جنيه مصري',
                                'SDG' => 'SDG - Sudanese Pound | ج.س - جنيه سوداني',
                                'AED' => 'AED - Emirati Dirham | د.إ - درهم إماراتي',
                                'SAR' => 'SAR - Saudi Riyal | ر.س - ريال سوداني',
                                'USD' => 'USD - US Dollar | $ - دولار أمريكي',
                                'DZD' => 'DZD - Algerian Dinar | د.ج - دينار جزائري',
                            ])
                            ->default('USD')
                            ->required() // This forces the user to have a value
                            ->columnSpan(2)
                            ->native(false),

                    ]),
            ]);
    }
}
