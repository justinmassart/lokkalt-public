<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public Order $order,
        public $orders = [],
    ) {

        $this->orders = Order::wherePaymentId($this->order->payment_id)->whereUserId($this->user->id)->get();

        foreach ($this->orders as $o) {
            $o->with([
                'shop',
                'items',
            ]);

            foreach ($o->items as $item) {
                $item->with([
                    'shopArticle.article',
                    'shopArticle.variant',
                ]);
            }
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Purschase Confirmed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.orders.order-confirmed',
            with: [
                'user' => $this->user,
                'orders' => $this->orders,
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
