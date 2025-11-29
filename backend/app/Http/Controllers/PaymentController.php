<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use App\Models\Invoice;
use App\Models\PaymentSetting;

class PaymentController extends Controller
{
    protected function stripe()
    {
        $secret = env('STRIPE_SECRET');
        return new StripeClient($secret);
    }

    public function createPaymentIntent(Request $request, $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        // amount should be in cents for Stripe
        $amount = (int) round($invoice->amount * 100);

        $stripe = $this->stripe();
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $amount,
            'currency' => $invoice->currency ?? 'usd',
            'metadata' => ['invoice_id' => $invoice->id]
        ]);

        return response()->json(['clientSecret' => $paymentIntent->client_secret]);
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig = $request->header('Stripe-Signature');

        // verify signature if STRIPE_WEBHOOK_SECRET is set
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');
        try {
            if ($webhookSecret) {
                $event = \Stripe\Webhook::constructEvent($payload, $sig, $webhookSecret);
            } else {
                $event = json_decode($payload);
            }
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $type = is_object($event) ? $event->type : ($event->type ?? null);
        $data = is_object($event) ? $event->data->object : ($event->data->object ?? null);

        if ($type === 'payment_intent.succeeded' && $data) {
            $invoiceId = $data->metadata->invoice_id ?? null;
            if ($invoiceId) {
                $invoice = Invoice::find($invoiceId);
                if ($invoice) {
                    $invoice->status = 'paid';
                    $invoice->save();
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
