<?php

namespace App\Filament\Resources\Categories\Schemas;

    use App\Models\Category;
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
                     ->columnSpanFull()

                    ->columns(4)
                    ->schema([
                        TextInput::make('name_ar')
                            ->label('الاسم (عربي)')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, $set) => $set('slug', Str::slug($state)))
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->columnSpan(2),

                        TextInput::make('name_en')
                            ->label('Name (EN)')
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(Category::class, 'slug', ignoreRecord: true)
                            ->columnSpanFull() ->columnSpan(2),

                        Textarea::make('description_ar')
                            ->label('الوصف (عربي)')
                            ->rows(3)
                            ->extraInputAttributes(['dir' => 'rtl']) ->columnSpan(2),

                        Textarea::make('description_en')
                            ->label('Description (EN)')
                            ->rows(3) ->columnSpan(2),
                    ])
            ]);
    }
}