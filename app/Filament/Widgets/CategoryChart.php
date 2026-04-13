<?php

namespace App\Filament\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class CategoryChart extends ApexChartWidget
{

protected static ?int $sort = 2;


    protected static ?string $chartId = 'categoryChart';

    protected static ?string $heading = 'Sales by Category';

    protected function getOptions(): array
    {
        return [
            'chart' => [
                'type' => 'donut',
                'height' => 300,
            ],
            'series' => [25, 15, 45, 10, 5],
            'labels' => ['Electronics', 'Fashion', 'Home', 'Groceries', 'Other'],
            'colors' => [
                '#0a483b', // Primary 500 (Sage)
                '#547f76', // Primary 400
                '#10b981', // Success 500 (Emerald)
                '#f59e0b', // Warning 500 (Amber)
                '#9ca3af', // Gray 400
            ],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '75%',
                        'labels' => [
                            'show' => true,
                            'name' => [
                                'show' => true,
                                'fontFamily' => 'inherit',
                                'fontWeight' => 600,
                            ],
                            'value' => [
                                'show' => true,
                                'fontFamily' => 'inherit',
                                'fontWeight' => 600,
                                'fontSize' => '20px',
                                'color' => '#0a483b',
                            ],
                            'total' => [
                                'show' => true,
                                'label' => 'Total',
                                'fontFamily' => 'inherit',
                                'color' => '#547f76',
                            ],
                        ],
                    ],
                ],
            ],
            'stroke' => [
                'width' => 2,
                'colors' => ['#ffffff'], // White gap between slices
            ],
            'legend' => [
                'position' => 'bottom',
                'fontFamily' => 'inherit',
            ],
            'dataLabels' => [
                'enabled' => false, // Keep it clean; totals are in the center
            ],
        ];
    }
}