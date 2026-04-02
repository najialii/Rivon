<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Account')
                    ->description('Manage user credentials and access')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2), 

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpan(2), 

                        Select::make('roles')
                            ->label('System Role')
                            ->relationship('roles', 'name') 
                            ->multiple() 
                            ->preload()
                            ->searchable()
                            ->native(false)
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('password')
                            ->password()
                            ->label(fn (string $context): string => $context === 'edit' ? 'New Password' : 'Password')
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->columnSpan(2),

                        DateTimePicker::make('email_verified_at')
                            ->label('Verification Date')
                            ->placeholder('Manually verify user')
                            ->columnSpan(2),

                        Select::make('is_active')
                            ->label('Active Status')
                            ->options([
                                true => 'Active',
                                false => 'Inactive',
                            ])
                            ->default(true)
                            ->required()
                            ->columnSpan(2),
                    ]),
            ]);
    }
}