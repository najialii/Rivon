<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name_en')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('name_ar')
                            ->required()
                            ->maxLength(255),

                        Select::make('account_type')
                            ->options([
                                'asset' => 'Asset',
                                'liability' => 'Liability',
                                'equity' => 'Equity',
                                'revenue' => 'Revenue',
                                'expense' => 'Expense',
                            ])
                            ->required()
                            ->native(false),

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
                            ->required()
                            ->columnSpan(2)
                            ->native(false),
                    ])->columns(2),

                Section::make('System Mapping')
                    ->schema([
                        Select::make('code')
                            ->label('Account Code | كود الحساب')
                            ->options([
                                // Assets
                                'cash_on_hand' => 'CASH_ON_HAND - Cash | صندوق النقدية',
                                'bank_account' => 'BANK_ACCOUNT - Bank | الحساب البنكي',
                                'accounts_receivable' => 'ACC_RECEIVABLE - Customers | ذمم مدينة',

                                'salary_expense' => 'SALARY_EXPENSE - Salaries | مصروف رواتب',
                                'office_rent' => 'OFFICE_RENT - Rent | إيجار مكتب',
                                'utility_bill' => 'UTILITY_BILL - Utilities | كهرباء ومياه',

                                'accounts_payable' => 'ACC_PAYABLE - Vendors | ذمم دائنة',
                                'loans' => 'LOANS - Bank Loans | قروض',
                            ])
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('custom_code')
                                    ->required(),
                            ])
                            ->helperText('Select a system handle or type a custom one. | اختر كود النظام أو اكتب كوداً مخصصاً.')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ])->columns(1),

                Section::make('Descriptions')
                    ->schema([
                        Textarea::make('description_en')
                            ->rows(3),

                        Textarea::make('description_ar')
                            ->rows(3),
                    ])->columns(2),
            ]);
    }
}
