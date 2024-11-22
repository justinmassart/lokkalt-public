<?php

namespace App\Filament\Dashboard\Resources\FranchiseResource\Pages;

use App\Filament\Dashboard\Resources\FranchiseResource;
use App\Filament\Pages\Dashboard;
use App\Models\Variant;
use Filament\Actions;
use Filament\Livewire\Notifications;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Stripe\StripeClient;

class EditFranchise extends EditRecord
{
    protected static string $resource = FranchiseResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('filament.edit_of') . ' ' . $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->before(function () {
                    $stripe = new StripeClient(config('services.stripe.secret'));

                    $canceledSub = $stripe->subscriptions->cancel($this->record->subscription->subscription_id);

                    if ($canceledSub->status !== 'canceled') {
                        Notifications::make()
                            ->title('Oups ... A problem has occured while trying to cancel your subscription. Please try again later or contact an administrator.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $this->record->subscription->delete();
                    $this->record->packs()->delete();

                    foreach ($this->record->shops as $shop) {
                        $shop->update([
                            'is_active' => false,
                        ]);

                        foreach ($shop->articles as $article) {
                            $article->update([
                                'is_active' => false,
                            ]);

                            Variant::whereArticleId($article->id)->update([
                                'is_visible' => false,
                            ]);
                        }
                    }

                    session()->flash('canceled', true);

                    $this->record->refresh();

                    foreach ($this->record->shops as $shop) {
                        $shop->articles()->delete();
                    }

                    session()->forget('franchise');
                    session()->forget('shop');
                })
                ->successRedirectUrl(Dashboard::getUrl()),
        ];
    }
}
