<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'stock_quantity',
        'discount',
        'discounted_price',
        'user_id',
        'images',
        'description',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeSearch($scope, $search)
    {
        $search = trim($search . '');
        if (!$search) {
            return;
        }

        $term = "%{$search}%";

        $scope->where(function ($query) use ($term) {
            $query->whereRaw("LOWER(name) LIKE LOWER(?)", [$term])
                ->orWhereRaw("LOWER(price) LIKE LOWER(?)", [$term])
                ->orWhereRaw("LOWER(description) LIKE LOWER(?)", [$term]);
        });
    }
}
