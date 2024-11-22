<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderAvailableMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public Order $order,
        public array $openingHours = [],
    ) {
        $this->order->with([
            'shop',
            'items',
        ]);

        foreach ($this->order->items as $item) {
            $item->with([
                'shopArticle.article',
                'shopArticle.variant'
            ]);
        }

        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $this->openingHours = collect($order->shop->opening_hours)->sortBy(function ($hours, $day) use ($daysOfWeek) {
            return array_search($day, $daysOfWeek);
        })->toArray();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your order is available',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.orders.order-available',
            with: [
                'user' => $this->user,
                'order' => $this->order,
                'openingHours' => $this->openingHours,
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
