<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentGatewayConfig extends Model
{
    protected $fillable=[
        'payment_gateway_id',
        'name',
        'mode',
        'is_active',

    ];
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
    public function gateway () : BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class,'payment_gateway_id');
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payment_gateway_config_id');
    }
}
