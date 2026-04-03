<?php

namespace App\Filament\Resources\Suppliers\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Supplier Information')
                    ->description('Bilingual details and contact information')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('supplier_name_ar')
                            ->label('اسم المورد (عربي)')
                            ->required()
                            ->extraInputAttributes(['dir' => 'rtl']),

                        TextInput::make('supplier_name_en')
                            ->label('Supplier Name (EN)')
                            ->required(),

                        TextInput::make('phone_num')
                            ->label('Phone Number')
                            ->tel()
                            ->required(),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email(),

                        Textarea::make('address_ar')
                            ->label('العنوان (عربي)')
                            ->rows(3)
                            ->extraInputAttributes(['dir' => 'rtl']),

                        Textarea::make('address_en')
                            ->label('Address (EN)')
                            ->rows(3),

                        TextInput::make('country')
                            ->label('Country')
                            ->searchable()
                            ->required(),


                    ]),
            ]);
    }
}