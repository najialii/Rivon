<?php

namespace App\Filament\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class RevenueChart extends ApexChartWidget
{
    protected static ?string $chartId = 'revenueChart';

    protected static ?string $heading = 'Revenue Analysis';

    protected function getOptions(): array
    {
        return [
            'chart' => [
                'type' => 'area',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'Revenue',
                    'data' => [7, 4, 6, 10, 14, 7, 5, 9, 10, 15, 13, 18],
                ],
            ],
            'xaxis' => [
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'colors' => '#6b7280', // Using your Gray 500 for axis text
                    ],
                ],
                'axisBorder' => [
                    'show' => false,
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'colors' => '#6b7280',
                    ],
                ],
            ],
            // MATCHING YOUR PALETTE
            'colors' => ['#0a483b'], // Your Primary 500 (Green Sage)
            
            'stroke' => [
                'curve' => 'smooth',
                'width' => 3,
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shadeIntensity' => 1,
                    'opacityFrom' => 0.45,
                    'opacityTo' => 0.05,
                    'stops' => [20, 100],
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'grid' => [
                'show' => true,
                'borderColor' => '#e5e7eb', // Your Gray 200 for subtle grid lines
                'strokeDashArray' => 4,
                'padding' => [
                    'left' => 2,
                    'right' => 2,
                ],
            ],
        ];
    }
}