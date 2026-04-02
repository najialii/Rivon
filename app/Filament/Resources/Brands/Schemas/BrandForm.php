<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use App\Models\Brand;


class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Brand Details')
                            ->description('Bilingual brand information')
                            ->columns(2)
                            ->schema([
                                TextInput::make('name_ar')
                                    ->label('اسم العلامة التجارية (عربي)')
                                    ->required()
                                    ->extraInputAttributes(['dir' => 'rtl']),

                                TextInput::make('name_en')
                                    ->label('Brand Name (EN)')
                                    ->required(),

                                FileUpload::make('logo')
                                    ->label('Brand Logo')
                                    ->image()
                                    ->directory('brand-logos')
                                    ->maxSize(1024) 
                                    ->columnSpanFull(),


                                        TextInput::make('slug')
                            // ->disabled()
                            ->dehydrated()
                            ->required()
                            // ->unique(Brand::class, 'slug', ignoreRecord: true)
                            ->columnSpanFull() ,



                                    Select::make('is_active')
                                        ->label('Is Active')
                                        ->options([
                                            1 => 'Yes',
                                            0 => 'No'
                                        ])
                                        ->default(1),


                                        
                            ]),
                    ]),            
            ]);
    }
}
