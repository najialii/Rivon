<?php
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Revenue', '$' . number_format(Order::where('status', 'confirmed')->sum('total_price'), 2))
                ->description('Total completed sales')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Pending Orders', Order::where('status', 'pending')->count())
                ->description('Need processing')
                ->icon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}