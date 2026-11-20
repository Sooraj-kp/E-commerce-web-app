<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Order extends Model
{

    public const STATUS_PENDING ='pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    public function canBeCancelled() :bool{
        return in_array($this->status,[
            self::STATUS_PENDING,self::STATUS_CONFIRMED,self::STATUS_PROCESSING
        ],true);

    }
    protected $fillable = [
        'user_id',
        'address_id',
        'order_number',
        'total_amount',
        'status',
        'assigned_staff_id',
        'cancellation_reason',
        'cancelled_at',
    ];
    public function items(): HasMany{           
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function address(): BelongsTo{
        return $this->belongsTo(Address::class);
    }
    public function payments() : HasMany {
        return $this->hasMany(Payment::class);
        
    }
    public function assignedStaff(): BelongsTo{
        return $this->belongsTo(User::class,'assigned_staff_id');
    }
    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at','asc');
    }
    public function canTransitionTo(string $newstatus ):bool{
        $transition = [
            self::STATUS_PENDING=>[self::STATUS_CONFIRMED,self::STATUS_CANCELLED],
            self::STATUS_CONFIRMED=>[self::STATUS_PROCESSING,self::STATUS_CANCELLED],
            self::STATUS_PROCESSING=>[self::STATUS_SHIPPED,self::STATUS_CANCELLED],
            self::STATUS_SHIPPED=>[self::STATUS_OUT_FOR_DELIVERY,],
            self::STATUS_OUT_FOR_DELIVERY=>[self::STATUS_DELIVERED,],
            self::STATUS_DELIVERED=>[],
            self::STATUS_CANCELLED=>[],


        ];
        return in_array($newstatus,$transition[$this->status] ?? [],true);
    }
}