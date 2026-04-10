<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpanFull()
                    ->schema([
                        // LEFT COLUMN (Main Details)
                        Grid::make(1)
                            ->schema([
                                Section::make('Product Identification')
                                    ->description('Bilingual details and classification')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name_ar')
                                            ->label('اسم المنتج (عربي)')
                                            ->required()
                                            ->extraInputAttributes(['dir' => 'rtl']),

                                        TextInput::make('name_en')
                                            ->label('Product Name (EN)')
                                            ->required(),

                                        TextInput::make('sku')
                                            ->label('SKU')
                                            ->unique(ignoreRecord: true),

                                        TextInput::make('munit')
                                            ->label('Measurement Unit')
                                            ->placeholder('e.g., kg, box, pcs'),

                                        Select::make('category_id')
                                            ->relationship('category', 'name_en')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->columnSpanFull(),

                                        Select::make('brand_id')
                                            ->relationship('brand', 'name_en')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([ 
                                                TextInput::make('name_en')->required(),
                                                TextInput::make('name_ar')->required(),
                                            ])
                                            ->columnSpanFull(),
                                    ]),

                                    Grid::make(3) // Creates a row for the units
    ->schema([
        TextInput::make('munit')
            ->label('Unit Type')
            ->placeholder('e.g., Box, Carton, Kg')
            ->required(),

        TextInput::make('unit_quantity')
            ->label('Qty per Unit')
            ->numeric()
            ->placeholder('e.g., 12')
            ->suffix('pcs'),

        TextInput::make('unit_weight')
            ->label('Weight per Unit')
            ->numeric()
            ->placeholder('e.g., 5.5')
            ->suffix('kg'),
    ]),

                                Section::make('Descriptions')
                                    ->description('Bilingual product information')
                                    ->schema([
                                        Textarea::make('description_ar')
                                            ->label('الوصف (عربي)')
                                            ->rows(3)
                                            ->extraInputAttributes(['dir' => 'rtl']),

                                        Textarea::make('description_en')
                                            ->label('Description (EN)')
                                            ->rows(3),
                                    ]),

                                Section::make('Detailed Cost Tracking')
                                    ->description('Breakdown of individual cost templates')
                                    ->schema([
                                        Repeater::make('costTemplates')
                                            ->relationship('costTemplates')
                                            ->schema([
                                                TextInput::make('name_en')->required(),
                                                TextInput::make('name_ar')->required(),
                                                TextInput::make('cost_price')->numeric()->prefix('$'),
                                                Select::make('cost_type')->options([
                                                    'manufacturing' => 'Manufacturing',
                                                    'shipping' => 'Shipping',
                                                    'customs' => 'Customs',
                                                ]),
                                            ])->columns(2)->collapsible(),
                                    ]),
                            ])
                            ->columnSpan(2),

                        // RIGHT COLUMN (Pricing & Status)
                        Grid::make(1)
                            ->schema([
                                Section::make('Pricing & Inventory')
                                    ->schema([
                                        Select::make('status')
                                            ->options([
                                                'active' => 'Active',
                                                'inactive' => 'Inactive',
                                                'archived' => 'Archived',
                                            ])
                                            ->default('active')
                                            ->required(),

                                        Select::make('currency')
                                            ->label('Main Currency')
                                            ->options([
                                                'SAR' => 'SAR - Saudi Riyal',
                                                'USD' => 'USD - US Dollar',
                                                'AED' => 'AED - Emirati Dirham',
                                                'SDG' => 'SDG - Sudanese Pound',
                                                'EGP' => 'EGP - Egyptian Pound',
                                            ])
                                            ->default('USD')
                                            ->required(),

                                        TextInput::make('original_price')
                                            ->label('Original Cost')
                                            ->numeric()
                                            ->prefix('$')
                                            ->required(),

                                        TextInput::make('wholesale_price')
                                            ->label('Wholesale Price')
                                            ->numeric()
                                            ->prefix('$')
                                            ->required(),

                                        TextInput::make('wholesale_min_price')
                                            ->label('Min Wholesale Price')
                                            ->numeric()
                                            ->prefix('$')
                                            ->required(),

                                        TextInput::make('retail_price')
                                            ->label('Retail Price')
                                            ->numeric()
                                            ->prefix('$')
                                            ->required()
                                            ->extraInputAttributes(['class' => 'font-bold']),
                                    ]),

                                Section::make('Visuals')
                                    ->schema([
                                        FileUpload::make('img_path')
                                            ->label('Product Image')
                                            ->image()
                                            ->directory('products')
                                            ->imageEditor()
                                            ->imageEditorAspectRatios(['1:1']),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}