<?php

namespace App\Livewire\Notifications;

use Livewire\Component;

class Popup extends Component
{
    public function mount()
    {
        $hasMessage = session()->has('popup');

        if ($hasMessage) {
            $this->dispatch('newPopup', type: 'warning', message: session()->get('popup'))->self();
            session()->forget('popup');
        };
    }

    public function render()
    {
        return view('livewire.notifications.popup');
    }
}
