<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'payment_gateway_config_id',
        'transaction_id',
        'gateway_order_id',
        'currency',
        'amount',
        'status',
        'paid_at',
        'failure_reason',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function gatewayConfig(): BelongsTo
    {
        return $this->belongsTo(
            PaymentGatewayConfig::class,
            'payment_gateway_config_id'
        );
    }
}
