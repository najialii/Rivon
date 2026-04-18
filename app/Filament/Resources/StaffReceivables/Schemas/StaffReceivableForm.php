<?php

namespace App\Filament\Resources\StaffReceivables\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StaffReceivableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Staff Receivable | ذمم الموظفين')
                    ->description('Track money owed by staff, advances issued, or collections made by staff')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([

                        Select::make('employee_id')
                            ->label('Staff Member | الموظف')
                            ->relationship('employee', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2),

                        Select::make('transaction_type')
                            ->label('Type | النوع')
                            ->options([
                                'receivable' => 'Receivable (Owed by Staff) | ذمة مدينة',
                                'advance'    => 'Salary Advance | سلفة راتب',
                                'collection' => 'Collection (Collected by Staff) | تحصيل',
                            ])
                            ->required()
                            ->native(false)
                            ->columnSpan(2),

                        TextInput::make('amount')
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

                        Select::make('status')
                            ->label('Status | الحالة')
                            ->options([
                                'pending'   => 'Pending | معلق',
                                'partial'   => 'Partially Settled | مسوى جزئياً',
                                'settled'   => 'Fully Settled | مسوى بالكامل',
                                'cancelled' => 'Cancelled | ملغى',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false)
                            ->columnSpan(2),

                        DatePicker::make('due_date')
                            ->label('Due Date | تاريخ الاستحقاق')
                            ->nullable()
                            ->columnSpan(2),

                        TextInput::make('amount_settled')
                            ->label('Amount Settled | المبلغ المسوى')
                            ->numeric()
                            ->prefix('$')
                            ->nullable()
                            ->helperText('Running total of recovered/settled amount')
                            ->columnSpan(2),

                        Select::make('receivable_account_id')
                            ->label('GL Account | حساب الذمم')
                            ->relationship('receivableAccount', 'name_en')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Required for automatic journal entry posting')
                            ->columnSpan(2),

                        // code field kept for salary compatibility
                        Select::make('code')
                            ->label('Code | الرمز')
                            ->options([
                                'SAL' => 'SAL - Salary',
                                'BON' => 'BON - Bonus',
                                'COM' => 'COM - Commission',
                                'OTH' => 'OTH - Other',
                            ])
                            ->default('OTH')
                            ->native(false)
                            ->columnSpan(2),

                        Textarea::make('notes')
                            ->label('Notes / Reason | الملاحظات')
                            ->nullable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
