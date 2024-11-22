<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportContactMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public array $data,
        public string $firstname = '',
        public string $lastname = '',
        public string $email = '',
        public string $about = '',
        public string $userMessage = '',
        public string $userLocale = '',
    ) {
        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
        $this->email = $data['email'];
        $this->about = $data['about'];
        $this->userMessage = $data['userMessage'];

        $this->userLocale = app()->currentLocale();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "$this->firstname $this->lastname - $this->about",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.support.customer-contact',
            with: [
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'about' => $this->about,
                'userMessage' => $this->userMessage,
                'userLocale' => $this->userLocale,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
