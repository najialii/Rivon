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
            // ->topNavigation()
                    ->sidebarCollapsibleOnDesktop()
                    ->brandName('Rivon')


                        ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
                    
        // ->maxContentWidth(Width::ScreenExtraLarge)
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon.ico'))

            ->colors([
                // 'primary' => Color::Amber,
                'primary' => [
                50 => '#f3f6f5',
                100 => '#e7edeb',
                200 => '#c2d1ce',
                300 => '#9db6b1',
                400 => '#547f76',
                500 => '#0a483b', // All buttons/icons use this
                600 => '#094135',
                700 => '#062b23',
                800 => '#05201b',
                900 => '#031612',
            ],
            // YOUR GLOBAL BACKGROUND & BORDER COLOR (Alabaster Cream)
            'gray' => [
                50 => '#ffffff',  // Page Background
                100 => '#ffffff', // Sidebar Background
                200 => '#ffffff', // ALL BORDERS (This hides them)
                300 => '#ffffff', // Input borders
                400 => '#a3a9a9', // Icons/Text (Dimmed)
                500 => '#7c8484', 
                600 => '#4a4f4f',
                700 => '#252828',
                800 => '#1a1d1d',   
                900 => '#0f1111',
            ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->font('tajawal', 'Tajawal, sans-serif')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
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
