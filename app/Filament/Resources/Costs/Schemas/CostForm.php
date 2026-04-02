<?php

namespace App\Filament\Resources\Costs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cost Profile')
                    ->description('Define pricing templates for supplies')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextInput::make('name_ar')
                            ->label('الاسم (عربي)')
                            ->required()
                            ->extraInputAttributes(['dir' => 'rtl'])->columnSpan(2),

                        TextInput::make('name_en')
                            ->label('Name (EN)')
                            ->required()->columnSpan(2),

                            

                        TextInput::make('cost_price')
                            ->label('Price Amount')
                            ->numeric()
                            ->prefix('$')
                            ->required()->columnSpan(2),

                              Select::make('currency')
                            ->label('Currency | العملة')
                            ->options([
                                'EGP' => 'EGP - Egyptian Pound | ج.م - جنيه مصري',
                                'SDG' => 'SDG - Sudanese Pound | ج.س - جنيه سوداني',
                                'AED' => 'AED - Emirati Dirham | د.إ - درهم إماراتي',
                                'SAR' => 'SAR - Saudi Riyal | ر.س - ريال سعودي',
                                'USD' => 'USD - US Dollar | $ - دولار أمريكي',
                                'DZD' => 'DZD - Algerian Dinar | د.ج - دينار جزائري',
                            ])  ->default('USD')
                            ->searchable()->columnSpan(2)
                            ->native(false)
                            ->required(),

                        Select::make('cost_type')
                            ->label('Category')
                            ->options([
                                'shipping' => 'Shipping & Freight | الشحن والتفريغ',
                                'customs' => 'Customs & Duties | الجمارك والرسوم',
                                'packaging' => 'Packaging Material | مواد التغليف',
                                'labor' => 'Labor & Handling | العمالة والمناولة',
                                'raw_materials' => 'Raw Materials | المواد الخام',
                                'manufacturing' => 'Manufacturing & Production | التصنيع والإنتاج',
                                'storage' => 'Storage & Warehousing | التخزين والمستودعات',
                                'marketing' => 'Marketing & Ads | التسويق والإعلانات',
                                'other' => 'Other Expenses | مصاريف أخرى',
                            ])
                            ->required()
                            ->native(false)->columnSpan(2),

                        Textarea::make('description_ar')
                            ->label('الوصف (عربي)')
                            ->columnSpanFull()
                            ->extraInputAttributes(['dir' => 'rtl'])->columnSpan(2),

                        Textarea::make('description_en')
                            ->label('Description (EN)')
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
