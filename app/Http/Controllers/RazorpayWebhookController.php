<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebhookEvent;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Get webhook secret
        $webhookSecret = config('services.razorpay.webhook_secret');

        if (empty($webhookSecret)) {
            return response()->json([
                'message' => 'Webhook secret is not configured'
            ], 500);
        }

        // 2. Verify webhook signature
        $webhookSignature = $request->header('X-Razorpay-Signature');

        if (!$webhookSignature) {
            return response()->json([
                'message' => 'Missing webhook signature'
            ], 400);
        }

        $payload = $request->getContent();

        $expectedSignature = hash_hmac(
            'sha256',
            $payload,
            $webhookSecret
        );

        if (!hash_equals($expectedSignature, $webhookSignature)) {
            return response()->json([
                'message' => 'Invalid webhook signature'
            ], 400);
        }

        // 3. Get unique event ID
        $eventId = $request->header('X-Razorpay-Event-Id');

        if (!$eventId) {
            return response()->json([
                'message' => 'Missing event ID'
            ], 400);
        }

        // 4. Decode JSON payload
        $data = json_decode($payload, true);

        if (!is_array($data)) {
            return response()->json([
                'message' => 'Invalid JSON payload'
            ], 400);
        }

        $eventType = $data['event'] ?? null;

        if (!$eventType) {
            return response()->json([
                'message' => 'Missing event type'
            ], 400);
        }

        // 5. Check for duplicate event
        if (WebhookEvent::where('event_id', $eventId)->exists()) {
            return response()->json([
                'message' => 'Event already received'
            ], 200);
        }

        // 6. Extract payment information
        $paymentData = $data['payload']['payment']['entity'] ?? null;

        try {

            // 7. Process webhook inside one transaction
            $result = DB::transaction(function () use (
                $eventId,
                $eventType,
                $data,
                $paymentData
            ) {

                // Create webhook event
                $webhookEvent = WebhookEvent::create([
                    'event_id' => $eventId,
                    'event_type' => $eventType,
                    'payload' => $data,
                    'status' => 'pending',
                ]);

                // Ignore unsupported event types
                if ($eventType !== 'payment.failed') {

                    $webhookEvent->update([
                        'status' => 'processed',
                        'processed_at' => now(),
                    ]);

                    return 'Unsupported event ignored';
                }

                // Validate payment information
                if (
                    !is_array($paymentData) ||
                    empty($paymentData['order_id'])
                ) {
                    $webhookEvent->update([
                        'status' => 'failed',
                        'error_message' => 'Payment information missing or invalid',
                    ]);

                    return 'Payment information unavailable';
                }

                // Find and lock the local payment
                $payment = Payment::where(
                    'gateway_order_id',
                    $paymentData['order_id']
                )->lockForUpdate()->first();

                // Check whether payment exists
                if (!$payment) {

                    $webhookEvent->update([
                        'status' => 'failed',
                        'error_message' => 'No matching payment record found',
                    ]);

                    return 'Payment record not found';
                }

                // Protect completed payments
                if ($payment->status === 'paid') {

                    $webhookEvent->update([
                        'status' => 'processed',
                        'processed_at' => now(),
                    ]);

                    return 'Payment already completed';
                }

                // Record payment failure
                if ($payment->status === 'pending') {

                    $payment->update([
                        'failure_reason' => $paymentData['error_description']
                            ?? 'Payment failed',
                    ]);

                    $webhookEvent->update([
                        'status' => 'processed',
                        'processed_at' => now(),
                    ]);

                    return 'Payment failure recorded successfully';
                }

                // Handle unexpected payment status
                $webhookEvent->update([
                    'status' => 'failed',
                    'error_message' => 'Payment is not in a processable state',
                ]);

                return 'Payment is not in a processable state';
            });

        } catch (QueryException $e) {

            // Handle duplicate event delivery
            if (WebhookEvent::where('event_id', $eventId)->exists()) {
                return response()->json([
                    'message' => 'Event already received'
                ], 200);
            }

            throw $e;
        }

        // 8. Return response
        return response()->json([
            'message' => $result
        ], 200);
    }
}
