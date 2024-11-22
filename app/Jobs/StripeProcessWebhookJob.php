<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stripe\StripeClient;

class StripeProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $eventID = $this->event->id;
        $stripeEvent = null;

        try {
            $stripeEvent = $stripe->events->retrieve($eventID);
        } catch (\Exception $e) {
            return;
        }

        if ($this->event->type !== $stripeEvent->type) {
            return;
        }

        /*
        Here are the events that Stripe send when subscribing, choose the ones to listen to :

        # WHEN CHOOSING SUBSCRIPTION BEFORE PAYING
            - payment_intent.created
            - customer.subscription.created
            - invoice.created
            - invoice.finalized

        # AFTER PAYING
            - charge.succeeded
            - payment_method.attached
            - customer.subscription.updated
            - payment_intent.succeeded
            - invoice.updated
            - invoice.paid
            - invoice.payment_succeeded

        # AFTER UPDATING THE SUBSCRIPTION
            -


        */

        switch ($this->event->type) {
            case 'charge.captured':
                $charge = $this->event->data->object;
                break;
            case 'invoice.upcoming':
                $invoice = $this->event->data->object;
                break;
            default:
                return;
        }
    }
}
