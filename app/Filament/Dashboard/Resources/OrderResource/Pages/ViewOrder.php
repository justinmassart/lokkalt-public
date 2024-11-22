<?php

namespace App\Filament\Dashboard\Resources\OrderResource\Pages;

use App\Filament\Dashboard\Resources\OrderResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\Attributes\On;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string|Htmlable
    {
        $total = $this->record->sub_total;

        return __('filament.order_ref').' '.$this->record->reference.' - '.$total.'€';
    }

    #[On('refreshOrderStatus')]
    public function refresh(): void
    {
        $this->refreshFormData([
            'status',
        ]);
    }
}
