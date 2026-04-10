<?php

namespace App\Filament\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class InventoryChart extends ApexChartWidget
{
    protected static ?string $chartId = 'inventoryChart';

    protected static ?string $heading = 'Inventory by Category';

    protected function getOptions(): array
    {
        return [
            'chart' => [
                'type' => 'treemap',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'data' => [
                        ['x' => 'Electronics', 'y' => 450],
                        ['x' => 'Fashion', 'y' => 280],
                        ['x' => 'Home & Garden', 'y' => 190],
                        ['x' => 'Automotive', 'y' => 110],
                        ['x' => 'Services', 'y' => 65],
                    ],
                ],
            ],
            // Use your Primary 500, 400, and 300 for a professional monochromatic look
            'colors' => [
                '#0a483b', // Primary 500 (Sage)
                '#547f76', // Primary 400
                '#9db6b1', // Primary 300
                '#c2d1ce', // Primary 200
            ],
            'plotOptions' => [
                'treemap' => [
                    'distributed' => true,
                    'enableShades' => false,
                ],
            ],
            'stroke' => [
                'width' => 2,
                'colors' => ['#ffffff'], // White borders make the blocks pop
            ],
            'dataLabels' => [
                'enabled' => true,
                'style' => [
                    'fontSize' => '14px',
                    'fontFamily' => 'inherit',
                    'fontWeight' => 'bold',
                ],
                'offsetY' => -4,
            ],
            'legend' => [
                'show' => false,
            ],
        ];
    }
}