<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersList extends Component
{
    use WithPagination;

    #[Computed]
    public function orders()
    {
        return Order::whereUserId(auth()->user()->id)
            ->with([
                'items.shopArticle.article.images',
                'items.shopArticle.variant',
                'user',
                'shop',
            ])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
    }

    // TODO: display refunded articles status

    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
    }

    public function render()
    {
        return view('livewire.orders.orders-list');
    }
}
