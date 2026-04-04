<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;

class InvoiceForm
{
        public static function configure(Schema $schema): Schema

    {
        return $schema
            ->components([
                Section::make('Invoice Information')
                    ->schema([
                        TextInput::make('invoice_number')
                            ->label('Invoice Number')
                            ->disabled()
                            ->default(fn () => (new \App\Models\Invoice())->generateInvoiceNumber()),

                        Select::make('customer_id')
                            ->label('Customer')
                            ->options(Customer::pluck('name_en', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => 
                                $set('order_id', null) // Reset order when customer changes
                            ),

                        Select::make('order_id')
                            ->label('Related Order (Optional)')
                            ->options(function (callable $get) {
                                $customerId = $get('customer_id');
                                if (!$customerId) return [];
                                
                                return Order::where('customer_id', $customerId)
                                    ->whereDoesntHave('invoices')
                                    ->where('status', '!=', 'cancelled')
                                    ->with('product')
                                    ->get()
                                    ->mapWithKeys(function ($order) {
                                        return [$order->id => "Order #{$order->id} - {$order->product->name_en} ({$order->quantity} x ${$order->total_price})"];
                                    });
                            })
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                if ($state) {
                                    $order = Order::find($state);
                                    if ($order) {
                                        // Pre-fill with order data
                                        $set('subtotal', $order->total_price);
                                        $set('tax_total', $order->total_price * 0.10); // 10% tax
                                        $set('total_amount', $order->total_price * 1.10);
                                        
                                        // Add order as invoice item
                                        $set('items', [[
                                            'description' => $order->product->name_en,
                                            'quantity' => $order->quantity,
                                            'unit_price' => $order->total_price / $order->quantity,
                                        ]]);
                                    }
                                }
                            }),

                        DatePicker::make('issue_date')
                            ->label('Issue Date')
                            ->default(now())
                            ->required(),

                        DatePicker::make('due_date')
                            ->label('Due Date')
                            ->default(now()->addDays(30))
                            ->required(),

                        Select::make('currency')
                            ->label('Currency')
                            ->options([
                                'USD' => 'USD',
                                'EUR' => 'EUR',
                                'GBP' => 'GBP',
                            ])
                            ->default('USD')
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'paid' => 'Paid',
                                'overdue' => 'Overdue',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Invoice Items')
                    ->schema([
                        Repeater::make('items')
                            ->label('Items')
                            ->schema([
                                TextInput::make('description')
                                    ->label('Description')
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        $unitPrice = $get('unit_price');
                                        $subtotal = $state * $unitPrice;
                                        $set('subtotal', number_format($subtotal, 2));
                                    }),

                                TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->step(0.01)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        $quantity = $get('quantity');
                                        $subtotal = $quantity * $state;
                                        $set('subtotal', number_format($subtotal, 2));
                                    }),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->step(0.01)
                                    ->disabled()
                                    ->dehydrated(false),
                            ])
                            ->columns(4)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['description'] ?? null)
                            ->defaultItems(1),
                    ])
                    ->collapsible(),

                Section::make('Tax & Totals')
                    ->schema([
                        TextInput::make('tax_percentage')
                            ->label('Tax Percentage (%)')
                            ->numeric()
                            ->step(0.1)
                            ->default(10)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $subtotal = $get('subtotal') ?? 0;
                                $taxTotal = $subtotal * ($state / 100);
                                $totalAmount = $subtotal + $taxTotal;
                                
                                $set('tax_total', number_format($taxTotal, 2));
                                $set('total_amount', number_format($totalAmount, 2));
                            }),

                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->step(0.01)
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('tax_total')
                            ->label('Tax Total')
                            ->numeric()
                            ->step(0.01)
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->step(0.01)
                            ->required()
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3),
                    ])
                    ->collapsible(),
            ]);
    }
}
