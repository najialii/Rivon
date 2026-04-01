<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
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


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            // ->topNavigation()
                    ->sidebarCollapsibleOnDesktop()
                    ->brandName('Rivon')
                    ->brandLogo(asset('storage/images/file.svg'))
                    
        ->maxContentWidth(Width::ScreenExtraLarge)
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
                    500 => '#0a483b', // Your Aqua Deep
                    600 => '#094135',
                    700 => '#062b23',
                    800 => '#05201b',
                    900 => '#031612',
                ],
                'gray' => [
               50 => '#fbfbfb',  // Your Alabaster
    100 => '#fbfbfb', // Match the background
    200 => '#fbfbfb', // THIS removes the annoying borders globally
    300 => '#cbcece',
                    400 => '#a3a9a9',
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
