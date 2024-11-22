<?php

namespace App\Http\Controllers;

use App\Jobs\StripeProcessWebhookJob;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $webhookSecret = config('services.stripe.webhook_key');
        $allowedIPs = [
            '3.18.12.63',
            '3.130.192.231',
            '13.235.14.237',
            '13.235.122.149',
            '18.211.135.69',
            '35.154.171.200',
            '52.15.183.38',
            '54.88.130.119',
            '54.88.130.237',
            '54.187.174.169',
            '54.187.205.235',
            '54.187.216.72',
        ];

        if (config('app.env') === 'local') {
            $allowedIPs[] = '127.0.0.1';
        }

        if (!in_array($request->server('REMOTE_ADDR'), $allowedIPs)) {
            return http_response_code(403);
        }

        $payload = $request->getContent();
        $sigHeader = $request->header('stripe-signature');

        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            return http_response_code(400);
        } catch (SignatureVerificationException $e) {
            return http_response_code(400);
        }

        StripeProcessWebhookJob::dispatch($event);

        return http_response_code(200);
    }
}
