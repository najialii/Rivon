<?php

    namespace App\Filament\Resources\Salaries\Schemas;

    use Filament\Schemas\Schema;
    use Filament\Forms\Form;
    use Filament\Schemas\Components\Section;
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Components\Textarea;
    use Filament\Schemas\Components\Grid;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\FileUpload;


    class SalaryForm
    {
        public static function configure(Schema $schema): Schema
        {
            return $schema
                ->components([
                        Select::make('employee_id')
                        ->searchable()
                        ->preload()
                        ->relationship('employee', 'name')
                        ->label('Employee ID')
                        ->required(),

                        TextInput::make('amount')
                        ->label('Salary Amount')
                        ->numeric()
                        ->prefix('$')
                        ->required(),


        Select::make('currency')
        ->label('Currency | العملة')
        ->options([
            'EGP' => 'EGP - Egyptian Pound | ج.م - جنيه مصري',
            'SDG' => 'SDG - Sudanese Pound | ج.س - جنيه سوداني',
            'AED' => 'AED - Emirati Dirham | د.إ - درهم إماراتي',
            'SAR' => 'SAR - Saudi Riyal | ر.س - ريال سوداني',
            'USD' => 'USD - US Dollar | $ - دولار أمريكي',
            'DZD' => 'DZD - Algerian Dinar | د.ج - دينار جزائري',
        ])
        ->default('USD')
        ->required() 
        // ->dehydrated()
        
        ->columnSpan(2)
        ->native(false),



                ]);
        }
    }
