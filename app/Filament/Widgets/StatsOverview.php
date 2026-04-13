<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
protected static ?int $sort = -1;
    protected function getStats(): array
    {
        return [
            // 1. Revenue - Using your 'success' emerald green
            Stat::make('Total Revenue', '$152.4k')
                ->description('32% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 10, 5, 12, 18, 14, 25]) 
                ->color('success'), // Matches your Success 500: #10b981

            // 2. Active Orders - Using your 'warning' amber
            Stat::make('Active Orders', '84')
                ->description('5 pending logistics')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->chart([15, 12, 10, 8, 12, 15, 18])
                ->color('warning'), // Matches your Warning 500: #f59e0b

            // 3. New Registrations - MATCHED TO YOUR PRIMARY BRAND COLOR
            Stat::make('New Registrations', '1,240')
                ->description('12% increase')
                ->descriptionIcon('heroicon-m-user-plus')
                ->chart([1, 4, 8, 12, 15, 18, 22])
                ->color('primary'), // Matches your Green Sage 500: #0a483b
        ];
    }
}