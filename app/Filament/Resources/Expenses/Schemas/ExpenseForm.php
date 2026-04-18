<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Force is_standalone = true for all records created via this resource
                Hidden::make('is_standalone')->default(true),

                Section::make('Expense Details | تفاصيل المصروف')
                    ->description('Record a standalone business expense')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextInput::make('name_en')
                            ->label('Expense Name (EN)')
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('name_ar')
                            ->label('اسم المصروف (عربي)')
                            ->required()
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->columnSpan(2),

                        Select::make('cost_type')
                            ->label('Category | الفئة')
                            ->options([
                                'rent'          => 'Rent & Utilities | الإيجار والمرافق',
                                'salaries'      => 'Salaries & Wages | الرواتب والأجور',
                                'shipping'      => 'Shipping & Freight | الشحن والتفريغ',
                                'customs'       => 'Customs & Duties | الجمارك والرسوم',
                                'packaging'     => 'Packaging Material | مواد التغليف',
                                'labor'         => 'Labor & Handling | العمالة والمناولة',
                                'raw_materials' => 'Raw Materials | المواد الخام',
                                'manufacturing' => 'Manufacturing & Production | التصنيع والإنتاج',
                                'storage'       => 'Storage & Warehousing | التخزين والمستودعات',
                                'marketing'     => 'Marketing & Ads | التسويق والإعلانات',
                                'travel'        => 'Travel & Transport | السفر والتنقل',
                                'maintenance'   => 'Maintenance & Repairs | الصيانة والإصلاحات',
                                'office'        => 'Office Supplies | مستلزمات المكتب',
                                'other'         => 'Other Expenses | مصاريف أخرى',
                            ])
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->columnSpan(2),

                        Select::make('status')
                            ->label('Status | الحالة')
                            ->options([
                                'pending'  => 'Pending Approval | في انتظار الموافقة',
                                'approved' => 'Approved | معتمد',
                                'rejected' => 'Rejected | مرفوض',
                                'paid'     => 'Paid | مدفوع',
                            ])
                            ->default('approved')
                            ->required()
                            ->native(false)
                            ->columnSpan(2),

                        TextInput::make('cost_price')
                            ->label('Amount | المبلغ')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->columnSpan(2),

                        Select::make('currency')
                            ->label('Currency | العملة')
                            ->options([
                                'EGP' => 'EGP - Egyptian Pound | ج.م',
                                'SDG' => 'SDG - Sudanese Pound | ج.س',
                                'AED' => 'AED - Emirati Dirham | د.إ',
                                'SAR' => 'SAR - Saudi Riyal | ر.س',
                                'USD' => 'USD - US Dollar | $',
                                'DZD' => 'DZD - Algerian Dinar | د.ج',
                            ])
                            ->default('USD')
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->columnSpan(2),

                        DatePicker::make('expense_date')
                            ->label('Expense Date | تاريخ المصروف')
                            ->default(now())
                            ->required()
                            ->columnSpan(2),

                        Select::make('paid_by_user_id')
                            ->label('Paid By | دفع بواسطة')
                            ->relationship('paidBy', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->columnSpan(2),

                        Select::make('expense_account_id')
                            ->label('Expense GL Account | حساب المصروف')
                            ->relationship('expenseAccount', 'name_en')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Required for automatic journal entry posting')
                            ->columnSpan(2),

                        Textarea::make('description_en')
                            ->label('Description (EN)')
                            ->columnSpan(2),

                        Textarea::make('description_ar')
                            ->label('الوصف (عربي)')
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->columnSpan(2),

                        FileUpload::make('receipt_path')
                            ->label('Receipt / Supporting Document | الإيصال')
                            ->directory('expense-receipts')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                            ->maxSize(5120)
                            ->nullable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
