<?php

namespace App\Filament\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class OrderChart extends ApexChartWidget
{
    protected static ?string $chartId = 'orderChart';
    protected static ?string $heading = 'Order Fulfillment';

    protected function getOptions(): array
    {
        return [
            'chart' => [
                'type' => 'radialBar',
                'height' => 300,
            ],
            'series' => [75],
            'plotOptions' => [
                'radialBar' => [
                    'hollow' => [
                        'size' => '70%',
                    ],
                    'track' => [
                        'background' => '#e7edeb', // Using your primary 100 for the background track
                    ],
                    'dataLabels' => [
                        'show' => true,
                        'name' => [
                            'show' => true,
                            'fontFamily' => 'inherit',
                            'color' => '#547f76', // Using your primary 400 for the label text
                        ],
                        'value' => [
                            'show' => true,
                            'fontFamily' => 'inherit',
                            'fontWeight' => 600,
                            'fontSize' => '24px',
                            'color' => '#0a483b', // Using your primary 500 for the percentage
                        ],
                    ],
                ],
            ],
            'stroke' => [
                'lineCap' => 'round',
            ],
            'labels' => ['Completed Orders'],
            'colors' => ['#0a483b'], // Your brand Primary 500
        ];
    }
}