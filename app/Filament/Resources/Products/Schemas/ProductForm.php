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

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpanFull()
                    ->schema([
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
    ->columnSpan(1),

                                        TextInput::make('munit')
                                            ->label('Measurement Unit')
                                            ->placeholder('e.g., kg, box, pcs')
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Descriptions')
                                    ->description('Bilingual product information')
                                    ->schema([
                                        Textarea::make('description_ar')
                                            ->label('الوصف (عربي)')
                                            ->rows(5)
                                            ->extraInputAttributes(['dir' => 'rtl']),

                                        Textarea::make('description_en')
                                            ->label('Description (EN)')
                                            ->rows(5),
                                    ]),
                            ])
                            ->columnSpan(2),

                        Grid::make(1)
                            ->schema([
                                Section::make('Visuals')
                                    ->description('Product imagery')
                                    ->schema([
                                        FileUpload::make('img_path')
                                            ->label('Product Image')
                                            ->image()
                                            ->directory('products')
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '1:1',
                                            ]),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}