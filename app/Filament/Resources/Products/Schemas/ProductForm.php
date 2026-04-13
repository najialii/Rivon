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
                        // LEFT COLUMN (Main Details & Costs)
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

                                        TextInput::make('measurement_unit')
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

                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('unit_type')
                                            ->label('Unit Type')
                                            ->placeholder('e.g., Box, Carton')
                                            ->required(),

                                        TextInput::make('unit_quantity')
                                            ->label('Qty per Unit')
                                            ->numeric()
                                            ->placeholder('12')
                                            ->suffix('pcs'),

                                        TextInput::make('unit_weight')
                                            ->label('Weight per Unit')
                                            ->numeric()
                                            ->placeholder('5.5')
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
                                    ->description('Breakdown of individual costs')
                                    ->schema([
                                        Repeater::make('costs') 
                                            ->relationship('costs')
                                            ->schema([
                                                TextInput::make('name_en')
                                                    ->label('Cost Item (EN)')
                                                    ->required(),

                                                TextInput::make('name_ar')
                                                    ->label('البند (عربي)')
                                                    ->required()
                                                    ->extraInputAttributes(['dir' => 'rtl']),

                                                Select::make('cost_type')
                                                    ->options([
                                                        'manufacturing' => 'Manufacturing',
                                                        'shipping' => 'Shipping & Freight',
                                                        'customs' => 'Customs & Duties',
                                                        'packaging' => 'Packaging Material',
                                                        'other' => 'Other',
                                                    ])
                                                    ->required(),

                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('cost_price')
                                                            ->label('Amount')
                                                            ->numeric()
                                                            ->prefix('$')
                                                            ->required()
                                                            ->live(onBlur: true),

                                                        Select::make('currency')
                                                            ->label('Currency')
                                                            ->options([
                                                                'SAR' => 'SAR',
                                                                'USD' => 'USD',
                                                                'AED' => 'AED',
                                                                'SDG' => 'SDG',
                                                                'EGP' => 'EGP',
                                                            ])
                                                            ->default('USD')
                                                            ->required(),
                                                    ]),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->cloneable()
                                            ->live()
                                            ->afterStateUpdated(function (callable $get, callable $set) {
                                                $costs = $get('costs') ?? [];
                                                $total = collect($costs)->sum(fn ($item) => (float) ($item['cost_price'] ?? 0));
                                                $set('original_price', number_format($total, 2, '.', ''));
                                            }),
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
                                            ->required()
                                            ->live()
                                            ->dehydrated(),

                                        TextInput::make('original_price')
                                            ->label('Total Original Cost')
                                            ->numeric()
                                            ->prefix('$')
                                            ->readonly()
                                            ->default(0)
                                            ->dehydrated()
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
                                            ->default(0)
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