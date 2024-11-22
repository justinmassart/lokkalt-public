<?php

namespace App\Filament\Dashboard\Resources\PackResource\Pages;

use App\Filament\Dashboard\Resources\PackResource;
use App\Models\FranchiseSubscription;
use Filament\Resources\Pages\Page;

class SubscriptionConfirmation extends Page
{
    protected static string $resource = PackResource::class;

    protected static string $model = FranchiseSubscription::class;

    protected static string $view = 'filament.dashboard.resources.pack-resource.pages.subscription-confirmation';
}
