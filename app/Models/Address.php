<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Address extends Model
{
    protected $fillable=[
        'user_id',
        'customer_id',
        'address_line',
        'city',
        'state',
        'postal_code',
        'country'
    ];
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);

    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
