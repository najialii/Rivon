<?php

namespace App\Filament\Resources\Categories\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Details')
                    ->description('Organize products into logical groups')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name_ar')
                            ->label('الاسم (عربي)')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, $set) => $set('slug', Str::slug($state)))
                            ->extraInputAttributes(['dir' => 'rtl']),

                        TextInput::make('name_en')
                            ->label('Name (EN)')
                            ->required(),

                        TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(Category::class, 'slug', ignoreRecord: true)
                            ->columnSpanFull(),

                        Textarea::make('description_ar')
                            ->label('الوصف (عربي)')
                            ->rows(3)
                            ->extraInputAttributes(['dir' => 'rtl']),

                        Textarea::make('description_en')
                            ->label('Description (EN)')
                            ->rows(3),
                    ])
            ]);
    }
}