<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Invoice;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment')
                    ->columns(2)
                    ->schema([
                        Select::make('invoice_id')
                            ->label('Invoice')
                            ->relationship('invoice', 'invoice_number')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $invoice = $state ? Invoice::find($state) : null;
                                if (!$invoice) {
                                    $set('customer_id', null);
                                    $set('currency', null);
                                    return;
                                }

                                $set('customer_id', $invoice->customer_id);
                                $set('currency', $invoice->currency);
                            }),

                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name_en')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(),

                        TextInput::make('amount')
                            ->numeric()
                            ->step(0.01)
                            ->required(),

                        Select::make('currency')
                            ->options([
                                'USD' => 'USD',
                                'SDG' => 'SDG',
                                'AED' => 'AED',
                                'EGP' => 'EGP',
                            ])
                            ->native(false)
                            ->required()
                            ->disabled(),

                        DatePicker::make('payment_date')
                            ->default(now())
                            ->required(),

                        Select::make('method')
                            ->options([
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'bank_transfer' => 'Bank Transfer',
                                'other' => 'Other',
                            ])
                            ->native(false)
                            ->default('cash')
                            ->required(),

                        TextInput::make('reference')
                            ->maxLength(255),

                        Textarea::make('notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

