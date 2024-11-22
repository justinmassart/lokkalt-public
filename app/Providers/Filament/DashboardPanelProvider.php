<?php

namespace App\Providers\Filament;

use App\Filament\Dashboard\Widgets\CreateFirstFranchise;
use App\Filament\Dashboard\Widgets\CreateFirstShop;
use App\Filament\Dashboard\Widgets\FranchiseSelector;
use App\Filament\Dashboard\Widgets\IncomeChart;
use App\Filament\Dashboard\Widgets\OrdersChart;
use App\Filament\Dashboard\Widgets\ShopSelector;
use App\Filament\Dashboard\Widgets\StatsOverview;
use App\Filament\Dashboard\Widgets\SubscribeToLokkalt;
use App\Filament\Pages\Dashboard;
use App\Http\Middleware\SetFilamentLocaleMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\View\View;

class DashboardPanelProvider extends PanelProvider
{
    public function register(): void
    {
        parent::register();
        FilamentView::registerRenderHook('panels::body.end', fn (): string => Blade::render("@vite('resources/js/app.js')"));
    }

    // TODO: check reset password (works on locale but not on prod)

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('dashboard')
            ->domain(config('app.domains.dashboard'))
            ->brandName('Lokkalt | Dashboard')
            ->colors([
                'primary' => '#375C47',
            ])
            ->favicon(asset('storage/svg/favicon.svg'))
            ->discoverResources(in: app_path('Filament/Dashboard/Resources'), for: 'App\\Filament\\Dashboard\\Resources')
            ->discoverPages(in: app_path('Filament/Dashboard/Pages'), for: 'App\\Filament\\Dashboard\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                StatsOverview::class,
                OrdersChart::class,
                IncomeChart::class,
                FranchiseSelector::class,
                ShopSelector::class,
                CreateFirstShop::class,
                CreateFirstFranchise::class,
                SubscribeToLokkalt::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetFilamentLocaleMiddleware::class,
            ])
            ->login()
            ->passwordReset()
            ->loginRouteSlug('dashboard-login')
            ->authMiddleware([
                Authenticate::class,
            ])
            ->default()
            ->unsavedChangesAlerts()
            ->renderHook(
                PanelsRenderHook::TOPBAR_START,
                fn (): View => view('filament.hooks.shop-switcher'),
            );
    }
}
