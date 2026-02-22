<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();
        $total = $cartItems->sum(function ($item) {
            $price = $item->product->discounted_price
                ?? $item->product->price;
            return $price * $item->quantity;
        });
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($id);

        $quantity = $request->quantity;
        if ($quantity > $product->stock_quantity) {
            return back()->with('error', 'Requested quantity exceeds stock.');
        }
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->stock_quantity) {
                return back()->with('error', 'Stock not sufficient');
            }

            $cartItem->update([
                'quantity' => $newQuantity
            ]);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        // return back()->with('success', 'Product added to cart!');
        return redirect()->route('cart.index')
            ->with('success', 'Product added to cart');
    }

    public function update(Request $request, $id)
    {
        $cart = CartItem::findOrFail($id);

        $quantity = max(1, $request->quantity);

        if ($quantity > $cart->product->stock_quantity) {
            $quantity = $cart->product->stock_quantity;
        }

        $cart->quantity = $quantity;
        $cart->save();

        $price = $cart->product->discounted_price
            ?? $cart->product->price;

        $subtotal = $price * $quantity;

        $total = CartItem::where('user_id', auth()->id())
            ->with('product')
            ->get()
            ->sum(function ($item) {
                $price = $item->product->discounted_price
                    ?? $item->product->price;
                return $price * $item->quantity;
            });

        return response()->json([
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'total' => number_format($total, 2, '.', ''),
            'quantity' => $quantity
        ]);
    }

    public function remove($id)
    {
        $cart = CartItem::findOrFail($id);
        $cart->delete();

        $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => true,
                'empty' => true
            ]);
        }

        $total = $cartItems->sum(function ($item) {
            $price = $item->product->discounted_price ?? $item->product->price;
            return $price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'empty' => false,
            'total' => number_format($total, 2, '.', '')
        ]);
    }
}
