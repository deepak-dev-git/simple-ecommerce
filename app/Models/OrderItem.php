<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    // Order item belongs to order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Order item belongs to product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
