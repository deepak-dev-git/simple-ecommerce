<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()->search($request->input('search'))
            ->where('status', true)
            ->latest()
            ->paginate(12);

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
        $exploreProducts = Product::where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('shop.show', compact(
            'product',
            'alreadyAddedQty',
            'exploreProducts'
        ));
    }

    public function suggestions(Request $request)
    {
        $search = $request->search;

        if (!$search) {
            return response()->json([]);
        }

        $products = Product::where('name', 'LIKE', "%{$search}%")
            ->where('status', true)
            ->select('id', 'name', 'images', 'price', 'discounted_price')
            ->limit(6)
            ->get();

        return response()->json($products);
    }
}
