<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
class OrderStatusHistory extends Model
{
    protected $fillable=[
        'order_id',
        'status',
        'description',
        'updated_by',
    ];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);

    }
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    
}
