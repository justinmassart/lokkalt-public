<?php

namespace App\Livewire\Support;

use App\Mail\SupportContactMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Validate(['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'])]
    public string $firstname = '';

    #[Validate(['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'])]
    public string $lastname = '';

    #[Validate(['required', 'string', 'email', 'regex:/^(?!.*@lokkalt\.).*$/'])]
    public string $email = '';

    #[Validate(['required', 'string', 'in:articles,shops,orders,evaluations,comments,other'])]
    public string $about = 'null';

    #[Validate(['required', 'string', 'min:20', 'max:500'])]
    public string $message = '';

    public bool $messageHasBeenSent = false;

    public bool $hasErrorOccured = false;

    public function sendMessage(): void
    {
        $this->validate();

        $data = [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'about' => $this->about,
            'userMessage' => $this->message,
        ];

        try {
            Mail::to(config('owner.mail'))
                ->queue(new SupportContactMail($data));

            $this->messageHasBeenSent = true;

            $this->reset([
                'firstname',
                'lastname',
                'email',
                'about',
                'message',
            ]);
        } catch (\Throwable $th) {
            $this->hasErrorOccured = true;
        }
    }

    public function render()
    {
        return view('livewire.support.contact-form');
    }
}
