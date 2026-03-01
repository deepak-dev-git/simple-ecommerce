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
        'delivered_at',
        'payment_method',
        'payment_status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
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

    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        $query->where(function ($q) use ($search) {
            $q->where('order_no', 'like', "%{$search}%")
                ->orWhere('total_amount', 'like', "%{$search}%");
            if (auth()->check() && auth()->user()->is_admin) {
                $q->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                });
            }
        });

        return $query;
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
