<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'primary', // Your Aqua Deep
                        'manager' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->iconColor('gray'),

                IconColumn::make('email_verified_at')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter()
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('Verified Status')
                    ->nullable()
                    ->placeholder('All Users')
                    ->trueLabel('Verified Only')
                    ->falseLabel('Unverified Only'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
