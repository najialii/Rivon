<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
// use Filament\Forms\Get;
// use Filament\Forms\Set;
use App\Models\Product;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Transaction Type')
                    ->schema([
                        Select::make('otype')
                            ->label('Order Type')
                            ->options([
                                'sales' => 'Customer Sale',
                                'purchase' => 'Supplier Purchase',
                                'quote' => 'Quotation',
                            ])
                            ->required()
                            ->live() 
                            ->native(false),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),

                Section::make('Entity Details')
                    ->schema([
                        Select::make('customer_id')
                            ->relationship('customer', 'name_ar')
                            ->searchable()
                            ->required()
                            ->visible(fn (Get $get) => in_array($get('otype'), ['sales', 'quote'])),

                        Select::make('supplier_id')
                            ->relationship('supplier', 'supplier_name_ar')
                            ->searchable()
                            ->required()
                            ->visible(fn (Get $get) => $get('otype') === 'purchase'),

                        DatePicker::make('order_date')
                            ->default(now())
                            ->required(),
                    ])->columns(2),

                Section::make('Order Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('order_items') 
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name_en')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('original_price', $product->original_price);
                                            $set('wholesale_price', $product->wholesale_price);
                                            $set('retail_price', $product->retail_price);
                                            $set('wholesale_min_price', $product->wholesale_min_price);
                                            $set('currency', $product->currency);
                                            $set('price', $product->retail_price); // Default to retail
                                        }
                                    })
                                    ->columnSpan(3),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live()
                                    ->columnSpan(1),

                                TextInput::make('price')
                                    ->label('Selling Price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->live()
                                    ->columnSpan(2),

                                // Hidden or collapsed fields to store the snapshot from migration
                                Grid::make(4)->schema([
                                    TextInput::make('original_price')->numeric()->disabled()->label('Cost'),
                                    TextInput::make('wholesale_price')->numeric()->disabled()->label('Wholesale'),
                                    TextInput::make('retail_price')->numeric()->disabled()->label('RRP'),
                                    TextInput::make('currency')->disabled(),
                                ])->hidden(fn (Get $get) => !$get('product_id')),

                            ])
                            ->columns(6)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotalPrice($get, $set);
                            }),
                    ]),

                Section::make('Finalization')
                    ->schema([
                        TextInput::make('total_price')
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->extraInputAttributes(['class' => 'font-bold text-lg text-primary-600']),
                    ])->collapsible(),
            ]);
    }

    /**
     * Logic to calculate the total price of the order
     */
    protected static function updateTotalPrice(Get $get, Set $set): void
    {
        $items = $get('items') ?? [];
        $total = 0;

        foreach ($items as $item) {
            $price = (float) ($item['price'] ?? 0);
            $qty = (int) ($item['quantity'] ?? 0);
            $total += $price * $qty;
        }

        $set('total_price', number_format($total, 2, '.', ''));
    }
}