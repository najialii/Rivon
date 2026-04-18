<?php

namespace App\Filament\Resources\Deposits\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DepositForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Force transaction_type = 'deposit' for all records via this resource
                Hidden::make('transaction_type')->default('deposit'),

                Section::make('Deposit Details | تفاصيل الإيداع')
                    ->description('Record a deposit from a public or staff source')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([

                        Select::make('source_type')
                            ->label('Source Type | نوع المصدر')
                            ->options([
                                'public' => 'Public (Walk-in) | عام',
                                'staff'  => 'Staff Member | موظف',
                            ])
                            ->required()
                            ->native(false)
                            ->live()
                            ->columnSpan(2),

                        Select::make('deposited_by_user_id')
                            ->label('Deposited By (Staff) | الموظف المودع')
                            ->relationship('depositedBy', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->visible(fn ($get) => $get('source_type') === 'staff')
                            ->columnSpan(2),

                        TextInput::make('amount')
                            ->label('Deposit Amount | مبلغ الإيداع')
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

                        DatePicker::make('payment_date')
                            ->label('Deposit Date | تاريخ الإيداع')
                            ->default(now())
                            ->required()
                            ->columnSpan(2),

                        Select::make('method')
                            ->label('Payment Method | طريقة الدفع')
                            ->options([
                                'cash'          => 'Cash | نقدي',
                                'bank_transfer' => 'Bank Transfer | تحويل بنكي',
                                'cheque'        => 'Cheque | شيك',
                                'mobile_money'  => 'Mobile Money | محفظة إلكترونية',
                                'card'          => 'Card | بطاقة',
                            ])
                            ->default('cash')
                            ->required()
                            ->native(false)
                            ->columnSpan(2),

                        TextInput::make('reference')
                            ->label('Reference / Receipt No. | المرجع')
                            ->nullable()
                            ->columnSpan(2),

                        Select::make('customer_id')
                            ->label('Customer (Optional) | العميل')
                            ->relationship('customer', 'name_ar')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->columnSpan(2),

                        Select::make('deposit_account_id')
                            ->label('Credit Account | حساب الإيداع')
                            ->relationship('depositAccount', 'name_en')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Leave blank to use the default cash account (1010)')
                            ->columnSpan(2),

                        Textarea::make('notes')
                            ->label('Notes | ملاحظات')
                            ->nullable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
