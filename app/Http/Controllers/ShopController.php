<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(12);

        return view('shop.index', compact('products'));
    }

    public function show(Product $product)
    {
        $alreadyAddedQty = 0;
        if (auth()->check()) {
            $cartItem = CartItem::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->first();
            if ($cartItem) {
                $alreadyAddedQty = $cartItem->quantity;
            }
        }
        return view('shop.show', compact('product', 'alreadyAddedQty'));
    }
}
