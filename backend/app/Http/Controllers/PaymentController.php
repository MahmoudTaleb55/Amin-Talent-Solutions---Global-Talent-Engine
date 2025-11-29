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

    // Create a connected account for the authenticated user (freelancer onboarding)
    public function createConnectedAccount(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Only freelancers can create connected accounts in this flow
        if (!$user->hasRole('freelancer')) {
            return response()->json(['error' => 'Only freelancers may create connected accounts'], 403);
        }

        $stripe = $this->stripe();
        try {
            $acct = $stripe->accounts->create([
                'type' => 'express',
                'email' => $user->email,
                'business_type' => 'individual'
            ]);

            $user->stripe_account_id = $acct->id;
            $user->save();

            if (class_exists('\App\Models\AuditLog')) {
                \App\Models\AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'create_connected_account',
                    'meta' => ['stripe_account_id' => $acct->id]
                ]);
            }

            return response()->json(['stripe_account_id' => $acct->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Admin: create connected account for any user
    public function createConnectedAccountForUser(Request $request, $userId)
    {
        $admin = $request->user();
        if (!$admin || !$admin->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = \App\Models\User::findOrFail($userId);

        $stripe = $this->stripe();
        try {
            $acct = $stripe->accounts->create([
                'type' => 'express',
                'email' => $user->email,
                'business_type' => 'individual'
            ]);

            $user->stripe_account_id = $acct->id;
            $user->save();

            if (class_exists('\App\Models\AuditLog')) {
                \App\Models\AuditLog::create([
                    'user_id' => $admin->id,
                    'action' => 'admin_created_connected_account',
                    'meta' => ['target_user_id' => $user->id, 'stripe_account_id' => $acct->id]
                ]);
            }

            return response()->json(['stripe_account_id' => $acct->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Create an account link for onboarding (admin or owner can request)
    public function createAccountLink(Request $request, $userId)
    {
        $requesting = $request->user();
        if (!$requesting || !($requesting->hasRole('admin') || $requesting->id == $userId)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = \App\Models\User::findOrFail($userId);
        if (!$user->stripe_account_id) {
            return response()->json(['error' => 'User has no stripe account id'], 400);
        }

        $stripe = $this->stripe();
        try {
            $link = $stripe->accountLinks->create([
                'account' => $user->stripe_account_id,
                'refresh_url' => url('/'),
                'return_url' => url('/'),
                'type' => 'account_onboarding',
            ]);

            return response()->json(['url' => $link->url]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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

    // Admin-only helper to simulate a payment_intent.succeeded event for testing
    public function simulateWebhook(Request $request, $invoiceId)
    {
        if (!auth()->user() || !auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $invoice = Invoice::find($invoiceId);
        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        $invoice->status = 'paid';
        $invoice->save();

        if (class_exists('\App\Models\AuditLog')) {
            \App\Models\AuditLog::create([
                'user_id' => auth()->id() ?? null,
                'action' => 'simulate_payment_succeeded',
                'meta' => ['invoice_id' => $invoice->id]
            ]);
        }

        return response()->json(['status' => 'simulated', 'invoice' => $invoice]);
    }
}
