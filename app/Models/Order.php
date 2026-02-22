<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_no',
        'total_amount',
        'status',
        'delivered_at'
    ];
    protected $casts = [
        'delivered_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {

            do {
                $orderNo = 'ORD-' . strtoupper(Str::random(10));
            } while (self::where('order_no', $orderNo)->exists());

            $order->order_no = $orderNo;
        });
    }
}
