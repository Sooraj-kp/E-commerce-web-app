<?php

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

test('payment failed webhook records failure reason', function () {

    DB::beginTransaction();

    try {
        $payment = Payment::findOrFail(10);

        $payload = [
            'event' => 'payment.failed',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_test_failed_123',
                        'order_id' => $payment->gateway_order_id,
                        'error_description' => 'Payment failed due to insufficient balance',
                    ],
                ],
            ],
        ];

        $rawPayload = json_encode($payload);

        $webhookSecret = config('services.razorpay.webhook_secret');

        $signature = hash_hmac(
            'sha256',
            $rawPayload,
            $webhookSecret
        );

        $response = $this->call(
            'POST',
            '/razorpay/webhook',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_RAZORPAY_SIGNATURE' => $signature,
                'HTTP_X_RAZORPAY_EVENT_ID' => 'evt_test_failed_123',
            ],
            $rawPayload
        );

        $response->assertOk();

        $payment->refresh();

        expect($payment->status)->toBe('pending');

        expect($payment->failure_reason)
            ->toBe('Payment failed due to insufficient balance');

    } finally {
        DB::rollBack();
    }
});