<?php

namespace App\Filament\Resources\JEntries\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;


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
                            ->disabled()
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

                Section::make('Memo')
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('memo_en')
                            ->label('Memo (EN)')
                            ->rows(2)
                            ->columnSpanFull(),

                        Textarea::make('memo_ar')
                            ->label('Memo (AR)')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Lines')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('lines')
                            ->relationship('lines')
                            ->schema([
                                Select::make('account_id')
                                    ->label('Account')
                                    ->relationship('account', 'name_en')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(4),

                                TextInput::make('debit')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        if ((float) $state > 0) {
                                            $set('credit', 0);
                                        }
                                    })
                                    ->columnSpan(2),

                                TextInput::make('credit')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        if ((float) $state > 0) {
                                            $set('debit', 0);
                                        }
                                    })
                                    ->columnSpan(2),

                                TextInput::make('description_en')
                                    ->label('Line Note (EN)')
                                    ->columnSpan(4),

                                TextInput::make('description_ar')
                                    ->label('Line Note (AR)')
                                    ->columnSpan(4),

                                TextInput::make('currency')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->default(fn (Get $get) => $get('../../currency'))
                                    ->columnSpan(2),
                            ])
                            ->columns(8)
                            ->defaultItems(2)
                            ->minItems(2)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $lines = $get('lines') ?? [];

                                $totalDebit = 0;
                                $totalCredit = 0;

                                foreach ($lines as $line) {
                                    $totalDebit += (float) ($line['debit'] ?? 0);
                                    $totalCredit += (float) ($line['credit'] ?? 0);
                                }

                                $set('total_debit', number_format($totalDebit, 2, '.', ''));
                                $set('total_credit', number_format($totalCredit, 2, '.', ''));
                            }),
                    ]),

                Section::make('Totals')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('total_debit')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('total_credit')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ]);
    }
}
