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
                        // TextInput::make('invoice_number')
                        //     ->label('Invoice Number')
                        //     ->default(fn () => (new \App\Models\Invoice())->generateInvoiceNumber()),

                        TextInput::make('invoice_number')
    ->label('Invoice Number')
    ->placeholder('Enter the number from the printed invoice')
    ->required()
    ->unique(ignoreRecord: true)
    ->helperText('Manual entry required. Match this to your paper records.')
    ->validationAttribute('invoice number'),


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
                                    ->withCount('order_items')
                                    ->get()
                                    ->mapWithKeys(function ($order) {
                                        return [$order->id => "Order #{$order->id} - {$order->order_items_count} items - Total {$order->total_price}"];
                                    });
                            })
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                if ($state) {
                                    $order = Order::with('order_items.product')->find($state);
                                    if ($order) {
                                        $subtotal = $order->order_items->sum(function ($item) {
                                            return ((float) $item->quantity) * ((float) $item->price);
                                        });
                                        $taxPercentage = (float) ($get('tax_percentage') ?? 0);
                                        $taxTotal = $subtotal * ($taxPercentage / 100);
                                        $totalAmount = $subtotal + $taxTotal;
                                        
                                        $set('subtotal', number_format($subtotal, 2, '.', ''));
                                        $set('tax_total', number_format($taxTotal, 2, '.', ''));
                                        $set('total_amount', number_format($totalAmount, 2, '.', ''));
                                        
                                        $set('items', $order->order_items->map(function ($item) {
                                            return [
                                                'description' => $item->product?->name_en ?? "Product #{$item->product_id}",
                                                'quantity' => (float) $item->quantity,
                                                'unit_price' => (float) $item->price,
                                            ];
                                        })->values()->all());
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
                                'EGP' => 'EGP - Egyptian Pound | ج.م - جنيه مصري',
                                'SDG' => 'SDG - Sudanese Pound | ج.س - جنيه سوداني',
                                'AED' => 'AED - Emirati Dirham | د.إ - درهم إماراتي',
                                'SAR' => 'SAR - Saudi Riyal | ر.س - ريال سعودي',
                                'USD' => 'USD - US Dollar | $ - دولار أمريكي',
                                'DZD' => 'DZD - Algerian Dinar | د.ج - دينار جزائري',
                            ])  ->default('USD')
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'partial' => 'Partially Paid',
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
                            ->relationship()
                            ->schema([
                                Select::make('tax_rate_id')
                                    ->label('Tax Rate')
                                    ->relationship('taxRate', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),

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
                                    ->dehydrated(),
                            ])
                            ->columns(6)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['description'] ?? null)
                            ->defaultItems(1)
                            ->live()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                $items = $get('items') ?? [];
                                $subtotal = 0.0;

                                foreach ($items as $item) {
                                    $quantity = (float) ($item['quantity'] ?? 0);
                                    $unitPrice = (float) ($item['unit_price'] ?? 0);
                                    $subtotal += $quantity * $unitPrice;
                                }

                                $taxPercentage = (float) ($get('tax_percentage') ?? 0);
                                $taxTotal = $subtotal * ($taxPercentage / 100);
                                $totalAmount = $subtotal + $taxTotal;

                                $set('subtotal', number_format($subtotal, 2, '.', ''));
                                $set('tax_total', number_format($taxTotal, 2, '.', ''));
                                $set('total_amount', number_format($totalAmount, 2, '.', ''));
                            }),
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
                            ->dehydrated(),

                        TextInput::make('tax_total')
                            ->label('Tax Total')
                            ->numeric()
                            ->step(0.01)
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->step(0.01)
                            ->required()
                            ->disabled()
                            ->dehydrated(),
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



// 1. The subtotal format mismatch

// In your Repeater, you are using number_format($subtotal, 2). This adds a comma (e.g., 1,250.00).

//     The Problem: HTML numeric inputs and database decimal columns hate commas. If a user tries to save an invoice over 1,000, the database will save it as 1.00 because the comma breaks the number.

//     The Fix: Always use number_format($value, 2, '.', '') (no thousands separator) or just round it.

// 2. The order_id state "Ghosting"

// When you select an order, you set the items. But if the user selects the wrong order and then changes it to a different order, the items repeater will append or behave weirdly because Filament doesn't always "flush" the repeater state cleanly via $set.

//     The Fix: You should set the items but also ensure the subtotal is recalculated immediately.

// 3. The tax_total rounding bug

// You are calculating tax_total in multiple places. If a user manually changes an item quantity, the Repeater's afterStateUpdated runs, but if they change the tax_percentage, a different logic runs.

//     The Fix: Centralize the "Total" calculation into a single private function or ensure the logic is identical.