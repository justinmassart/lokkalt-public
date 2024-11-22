<?php

namespace App\Mail;

use App\Models\Franchise;
use App\Models\FranchiseRegistrationToken;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FranchiseRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly User $user,
        public readonly Franchise $franchise,
        public readonly FranchiseRegistrationToken $registrationToken,
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You asked to register a franhcise on Lokkalt.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.franchises.franchise-registration',
            with: [
                'user' => $this->user,
                'franchise' => $this->franchise,
                'registrationToken' => $this->registrationToken,
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
