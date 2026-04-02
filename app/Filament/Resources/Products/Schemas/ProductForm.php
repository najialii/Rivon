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
                            ->required(),

                               Select::make('category_id')
                            ->relationship('category', 'name_en')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('munit')
                            ->label('Measurement Unit')
                            ->placeholder('e.g., kg, box, pcs'),
                    ]),

                Section::make('Visuals & Description')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('img_path')
                            ->label('Product Image')
                            ->image()
                            ->directory('products')
                            ->columnSpan(1),

                        Textarea::make('description_ar')
                            ->label('الوصف (عربي)')
                            ->rows(5)
                            ->extraInputAttributes(['dir' => 'rtl']),

                        Textarea::make('description_en')
                            ->label('Description (EN)')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}