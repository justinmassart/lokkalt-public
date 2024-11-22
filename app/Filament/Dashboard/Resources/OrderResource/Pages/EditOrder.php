<?php

namespace App\Filament\Dashboard\Resources\OrderResource\Pages;

use App\Filament\Dashboard\Resources\OrderResource;
use App\Mail\OrderDeliveredMail;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('filament.order_ref').' '.$this->record->reference;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('delivered')
                ->label(__('filament.mark_delivered'))
                ->disabled(static function (Model $record) {
                    return $record->status === 'available' ? false : true;
                })
                ->requiresConfirmation()
                ->action(static function (Model $record) {
                    $record->update([
                        'status' => 'delivered',
                    ]);

                    Mail::to($record->user->email)
                        ->queue(new OrderDeliveredMail($record->user, $record));
                }),
        ];
    }

    #[On('refreshOrderStatus')]
    public function refresh(): void
    {
        $this->refreshFormData([
            'status',
        ]);
    }
}
