<?php

namespace App\Filament\Resources\Costs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;

class CostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cost Profile')
                    ->description('Define pricing templates for supplies')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name_ar')
                            ->label('الاسم (عربي)')
                            ->required()
                            ->extraInputAttributes(['dir' => 'rtl']),

                        TextInput::make('name_en')
                            ->label('Name (EN)')
                            ->required(),

                        TextInput::make('cost_price')
                            ->label('Price Amount')
                            ->numeric()
                            ->prefix('$') // Change to your currency if needed
                            ->required(),

                        Select::make('cost_type')
                            ->label('Category')
                            ->options([
                                'shipping' => 'Shipping & Freight',
                                'customs' => 'Customs & Duties',
                                'packaging' => 'Packaging Material',
                                'labor' => 'Labor & Handling',
                                'other' => 'Other Expenses',
                            ])
                            ->required()
                            ->native(false),

                        Textarea::make('description_ar')
                            ->label('الوصف (عربي)')
                            ->columnSpanFull()
                            ->extraInputAttributes(['dir' => 'rtl']),

                        Textarea::make('description_en')
                            ->label('Description (EN)')
                            ->columnSpanFull(),
                    ])
            ]);
    }
}