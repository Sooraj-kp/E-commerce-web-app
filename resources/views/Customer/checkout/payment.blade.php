
@extends('layouts.customer')

@section('title', 'Complete Payment')

@section('content')

<div class="payment-page">

    <div class="payment-card">

        <div class="payment-icon">
            <span>₹</span>
        </div>

        <h1>Complete Your Payment</h1>
        <p class="payment-subtitle">
            Your order is almost ready. Complete your payment securely.
        </p>

        <div class="order-details">

            <div class="detail-row">
                <span>Order Number</span>
                <strong>{{ $order->order_number }}</strong>
            </div>

            <div class="detail-row">
                <span>Payment Method</span>
                <strong>Razorpay</strong>
            </div>

            <div class="detail-row amount-row">
                <span>Total Amount</span>
                <strong>₹{{ number_format($payment->amount, 2) }}</strong>
            </div>

        </div>

        <div class="secure-note">
            <span>🔒</span>
            <p>Secure payment powered by Razorpay</p>
        </div>

        <button type="button" id="pay-button" class="pay-button">
            <span id="button-text">Pay Now</span>
            <span>→</span>
        </button>

        <p class="payment-footer">
            Please do not refresh or close this page during payment.
        </p>

    </div>

</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    const payButton = document.getElementById('pay-button');

    payButton.addEventListener('click', function () {

        payButton.disabled = true;
        document.getElementById('button-text').textContent = 'Opening Payment...';

        const options = {
            key: @json(config('services.razorpay.key')),
            amount: {{ (int) round($payment->amount * 100) }},
            currency: 'INR',
            name: 'My E-commerce Store',
            description: 'Order Payment',
            order_id: @json($payment->gateway_order_id),

            handler: function (response) {

                const form = document.createElement('form');

                form.method = 'POST';
                form.action = @json(route('customer.payment.verify', $order->id));

                const fields = {
                    _token: @json(csrf_token()),
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature
                };

                for (const name in fields) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = fields[name];
                    form.appendChild(input);
                }

                document.body.appendChild(form);
                form.submit();
            },

            modal: {
                ondismiss: function () {
                    payButton.disabled = false;
                    document.getElementById('button-text').textContent = 'Pay Now';
                    alert('Payment window closed. Your order is still pending.');
                }
            }
        };

        const razorpay = new Razorpay(options);

        razorpay.on('payment.failed', function (response) {
            payButton.disabled = false;
            document.getElementById('button-text').textContent = 'Pay Now';

            alert('Payment Failed: ' + response.error.description);
        });

        razorpay.open();
    });
</script>

<style>
    .payment-page {
        min-height: 75vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 55px 20px;
        background: #f8fafc;
    }

    .payment-card {
        width: 100%;
        max-width: 520px;
        padding: 40px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.07);
        text-align: center;
    }

    .payment-icon {
        width: 65px;
        height: 65px;
        margin: 0 auto 20px;
        display: grid;
        place-items: center;
        border-radius: 18px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 30px;
        font-weight: 800;
    }

    .payment-card h1 {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 10px;
    }

    .payment-subtitle {
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .order-details {
        padding: 22px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
        font-size: 13px;
    }

    .detail-row span {
        color: #64748b;
    }

    .detail-row strong {
        color: #1e293b;
        text-align: right;
        overflow-wrap: anywhere;
    }

    .amount-row {
        border-bottom: none;
        padding-top: 20px;
    }

    .amount-row strong {
        font-size: 23px;
        color: #2563eb;
        font-weight: 800;
    }

    .secure-note {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 24px 0;
        color: #64748b;
        font-size: 12px;
    }

    .secure-note p {
        margin: 0;
    }

    .pay-button {
        width: 100%;
        padding: 15px 22px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        border: none;
        border-radius: 9px;
        background: #2563eb;
        color: white;
        font-size: 15px;
        font-weight: 750;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .pay-button:hover:not(:disabled) {
        background: #1d4ed8;
        transform: translateY(-2px);
    }

    .pay-button:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    .payment-footer {
        margin: 20px 0 0;
        color: #94a3b8;
        font-size: 11px;
        line-height: 1.5;
    }

    @media (max-width: 550px) {
        .payment-card {
            padding: 25px 20px;
        }

        .payment-card h1 {
            font-size: 22px;
        }

        .detail-row {
            font-size: 12px;
        }
    }
</style>

@endsection
