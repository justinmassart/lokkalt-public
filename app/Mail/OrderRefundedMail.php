<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderRefundedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public Order $order,
        public string $reason,
        public $items = [],
    ) {
        $this->order->with(['shop']);

        foreach ($this->order->items()->where('has_been_refunded', true)->get() as $item) {
            $item->with([
                'shopArticle.article',
                'shopArticle.variant',
            ]);
            $this->items[] = $item;
        }

        $items = collect($items);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your order has been refunded',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.orders.order-refunded',
            with: [
                'user' => $this->user,
                'order' => $this->order,
                'items' => $this->items,
                'reason' => $this->reason,
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
