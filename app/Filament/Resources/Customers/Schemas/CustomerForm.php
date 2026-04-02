<?php

namespace App\Filament\Resources\Customers\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')
                    ->description('Detailed profile for trade partners')
                    ->columnSpanFull()
                    ->columns(4) 
                    ->schema([
                        TextInput::make('name_ar')
                            ->label('Name (AR)')
                            ->required()
                            ->extraInputAttributes(['dir' => 'rtl']) ->columnSpan(2),

                        TextInput::make('name_en')
                            ->label('Name (EN)')
                            ->required() ->columnSpan(2),

                        TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->required() ->columnSpan(2),

                        TextInput::make('email')
                            ->label('Email')
                            ->email() ->columnSpan(2),

                        Textarea::make('address_ar')
                            ->label('Address (AR)')
                            ->rows(3)
                            ->columnSpanFull() // This makes it wide and centered
                            ->extraInputAttributes(['dir' => 'rtl']) ->columnSpan(2),

                        Textarea::make('address_en')
                            ->label('Address (EN)')
                            ->rows(3)
                            ->columnSpanFull() ->columnSpan(2),
                    ])
            ]);
    }
}