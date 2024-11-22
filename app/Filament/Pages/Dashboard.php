<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Request;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $activeNavigationIcon = 'heroicon-s-home';

    public function getTitle(): string|Htmlable
    {
        if (session()->has('shop')) {
            return __('titles.dashboard_of') . ' ' . session()->get('shop')->name . ' - ' . session()->get('shop')->postal_code . ' ' . session()->get('shop')->city;
        } else {
            return '';
        }
    }

    public function getColumns(): int|string|array
    {
        return 2;
    }

    // TODO: remove franchise sub from session once canceled sub

    public function mount(): void
    {
        if (strlen(app()->currentLocale()) > 3) {
            $preferredLang = Request::getPreferredLanguage();
            $lang = explode('_', $preferredLang)[0];
            app()->setLocale($lang);
        }

        $shop = session()->get('shop');
        $franchise = session()->get('franchise');

        if ($franchise) {
            $franchise->refresh();
        }

        if ($shop) {
            $franchise = $shop->franchise;
            session()->put('franchise', $franchise);
        } elseif (!$shop && !$franchise) {
            $userFranchisesCount = auth()->user()->franchises()->count();

            $franchise = $userFranchisesCount > 1 || $userFranchisesCount === 0 ? null : auth()->user()->franchises()->first()->load('subscription');

            if ($franchise) {
                session()->put('franchise', $franchise);
            }
        }

        if (session()->has('subscription')) {
            Notification::make()
                ->title(__('titles.sub_confirmation_notification'))
                ->success()
                ->send();
            session()->forget('subscription');
        }
    }
}
