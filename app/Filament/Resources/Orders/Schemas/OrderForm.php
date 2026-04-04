<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use App\Models\User;
use App\Models\Product;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer & Timing')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->required()
                            ->native(false),

                        DatePicker::make('order_date')
                            ->label('Order Date')
                            ->default(now())
                            ->required()
                            ->native(false),
                    ])->columns(2),

                Section::make('Product Selection')
                    ->schema([
                        Select::make('product_id')
                            ->label('Select Product')
                            ->options(Product::pluck('name_en', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                if ($state) {
                                    $product = Product::find($state);
                                    if ($product) {
                                        $qty = $get('quantity') ?? 1;
                                        $set('total_price', $qty * ($product->price ?? 0));
                                    }
                                }
                            }),

                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                $productId = $get('product_id');
                                if ($productId) {
                                    $product = Product::find($productId);
                                    if ($product) {
                                        $set('total_price', ($state ?? 1) * ($product->price ?? 0));
                                    }
                                }
                            }),
                    ])->columns(2),

                Section::make('Order Lifecycle')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false),

                        TextInput::make('total_price')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->dehydrated(),
                    ])->columns(2),

                Section::make('Summary')
                    ->schema([
                        Placeholder::make('summary_view')
                            ->label('Order Calculation')
                            ->content(function ($get) {
                                $productId = $get('product_id');
                                if (!$productId) return 'Please select a product.';
                                
                                $product = Product::find($productId);
                                $qty = $get('quantity') ?? 0;
                                $total = $qty * ($product->price ?? 0);

                                return "Unit Price: \${$product->price} | Quantity: {$qty} | Final: \${$total}";
                            }),
                    ])->collapsible(),
            ]);
    }
}