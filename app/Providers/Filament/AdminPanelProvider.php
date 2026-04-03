<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use App\Filament\Widgets\ProductStatsWidget;
use App\Filament\Widgets\TopSellingProductsWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Enums\Width;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->brandLogo(asset('images/rivonlogo.jpeg'))
            ->brandLogoHeight('2.5rem')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('20rem')
            ->sidebarFullyCollapsibleOnDesktop()
            ->brandName('Rivon')
            ->topNavigation(false)
            ->maxContentWidth(Width::Full)
            ->renderHook(
                'panels::head.start',
                fn (): string => '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">'
            )
            ->renderHook(
                'panels::body.start',
                fn (): string => '<div class="app-wrapper">'
            )
            ->renderHook(
                'panels::body.end',
                fn (): string => '</div>'
            )
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->favicon(asset('favicon.ico'))

            ->colors([
                // Your Original Color Scheme (Green Sage) - Complete with all keys
                'primary' => [
                    50 => '#f3f6f5',
                    100 => '#e7edeb',
                    200 => '#c2d1ce',
                    300 => '#9db6b1',
                    400 => '#547f76',
                    500 => '#0a483b', // Your original primary
                    600 => '#094135',
                    700 => '#062b23',
                    800 => '#05201b',
                    900 => '#031612',
                    950 => '#020e0b', // Added missing 950 key
                ],
                // Updated Gray Scale for Dark Mode Support
                'gray' => [
                    50 => '#f9fafb',   // Light backgrounds
                    100 => '#f3f4f6',  // Card backgrounds
                    200 => '#e5e7eb',  // Borders
                    300 => '#d1d5db',  // Hover states
                    400 => '#9ca3af',  // Disabled text
                    500 => '#6b7280',  // Secondary text
                    600 => '#4b5563',  // Primary text
                    700 => '#374151',  // Headings
                    800 => '#1f2937',  // Dark text
                    900 => '#111827',  // Darkest text
                    950 => '#030712', // Added missing 950 key
                ],
                // Modern SaaS Colors for actions - Complete with all keys
                'success' => [
                    50 => '#ecfdf5',
                    100 => '#d1fae5',
                    200 => '#a7f3d0',
                    300 => '#6ee7b7',
                    400 => '#34d399',
                    500 => '#10b981',
                    600 => '#059669',
                    700 => '#047857',
                    800 => '#065f46',
                    900 => '#064e3b',
                    950 => '#022c22', // Added missing 950 key
                ],
                'warning' => [
                    50 => '#fffbeb',
                    100 => '#fef3c7',
                    200 => '#fde68a',
                    300 => '#fcd34d',
                    400 => '#fbbf24',
                    500 => '#f59e0b',
                    600 => '#d97706',
                    700 => '#b45309',
                    800 => '#92400e',
                    900 => '#78350f',
                    950 => '#451a03', // Added missing 950 key
                ],
                'danger' => [
                    50 => '#fff1f2',
                    100 => '#ffe4e6',
                    200 => '#fecdd3',
                    300 => '#fda4af',
                    400 => '#fb7185',
                    500 => '#f43f5e',
                    600 => '#e11d48',
                    700 => '#be123c',
                    800 => '#9f1239',
                    900 => '#881337',
                    950 => '#4c0519', // Added missing 950 key
                ],
                // Add other colors that might be used
                'info' => [
                    50 => '#eff6ff',
                    100 => '#dbeafe',
                    200 => '#bfdbfe',
                    300 => '#93c5fd',
                    400 => '#60a5fa',
                    500 => '#3b82f6',
                    600 => '#2563eb',
                    700 => '#1d4ed8',
                    800 => '#1e40af',
                    900 => '#1e3a8a',
                    950 => '#172554', // Added missing 950 key
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->font('Tajawal', 'Tajawal, sans-serif')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                // ProductStatsWidget::class,
                // TopSellingProductsWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
                            FilamentApexChartsPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
