<?php

namespace App\Filament\Resources\JEntries\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;


class JEntriesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Journal Entry Header')
                    ->description('Primary transaction details')
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextInput::make('entry_number')
                            ->label('Entry #')
                            ->default('JE-' . date('Ymd') . '-' . strtoupper(str()->random(4)))
                            ->readonly()
                            ->required(),

                        DatePicker::make('entry_date')
                            ->label('Transaction Date')
                            ->default(now())
                            ->required(),

                        Select::make('currency')
                            ->options([
                                'USD' => 'USD ($)',
                                'SDG' => 'SDG (ج.س)',
                                'AED' => 'AED (د.إ)',
                                'EGP' => 'EGP (ج.م)',
                            ])
                            ->default('USD')
                            ->native(false)
                            ->required(),
                    ]),

                Section::make('Account & Reference')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('account_id')
                            ->label('Ledger Account')
                            ->relationship('account', 'name_en')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Grid::make(2)
                            ->schema([
                                Select::make('reference_type')
                                    ->label('Source Type')
                                    ->options([
                                        'supply' => 'Supply Shipment',
                                        'sale' => 'Customer Sale',
                                        'cost' => 'Operational Cost',
                                        'manual' => 'Manual Adjustment',
                                    ])
                                    ->required()
                                    ->native(false),

                                TextInput::make('reference_id')
                                    ->label('Reference ID / ID #')
                                    ->required(),
                            ]),

                        TextInput::make('debit')
                            ->label('Debit Amount')
                            ->numeric()
                            ->default(0)
                            ->prefix('$'),

                        TextInput::make('credit')
                            ->label('Credit Amount')
                            ->numeric()
                            ->default(0)
                            ->prefix('$'),

                        Textarea::make('description')
                            ->label('Internal Note')
                            ->columnSpanFull()
                            ->rows(2),
                    ])
            ]);
    }
}