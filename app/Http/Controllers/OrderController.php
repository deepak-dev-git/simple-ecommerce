<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use App\Models\Product;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id'
        ], [
            'address_id.required' => 'Please select a delivery address.',
        ]);

        $address = Address::where('id', $request->address_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$address) {
            return back()->with('error', 'Invalid delivery address selected.');
        }

        $cartItems = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Cart is empty');
        }

        DB::transaction(function () use ($cartItems, $address) {

            $total = 0;

            foreach ($cartItems as $item) {

                if ($item->quantity > $item->product->stock_quantity) {
                    throw new \Exception('Stock not sufficient');
                }

                $price = $item->product->discounted_price
                    ?? $item->product->price;

                $total += $price * $item->quantity;
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'address_id' => $address->id,
                'total_amount' => $total,
                'status' => 'pending'
            ]);

            foreach ($cartItems as $item) {

                $price = $item->product->discounted_price
                    ?? $item->product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $price
                ]);

                $item->product->decrement('stock_quantity', $item->quantity);
            }

            CartItem::where('user_id', auth()->id())->delete();
        });

        // return redirect()->route('shop.index')
        //     ->with('success', 'Order placed successfully!');
                return redirect()->route('order.success')
            ->with('success', 'Order placed successfully!');
    }


    public function index()
    {
        if (auth()->user()->is_admin) {
            $orders = Order::with('user')
                ->latest()
                ->paginate(10);

            return view('orders.index', compact('orders'));
        } else {
            $orders = Order::where('user_id', auth()->id())
                ->with('user')
                ->latest()
                ->paginate(10);
            return view('customer-orders.index', compact('orders'));
        }
    }

    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product'])
            ->findOrFail($id);
        if (auth()->user()->is_admin) {
            return view('orders.show', compact('order'));
        } else {
            return view('customer-orders.show', compact('order'));
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => ['required', Rule::in(OrderStatus::getScalarConstants())],
        ]);
        $dataToUpdate = [
            'status' => $request->status
        ];

        if ($order->status != OrderStatus::CANCELLED && $request->status == OrderStatus::CANCELLED) {
            foreach ($order->orderItems as $item){
                $product = Product::where('id', $item->product_id)->first();
                if ($product) {
                    $product->increment('stock_quantity', $item->quantity);
                }
            }
        }

        $order->update($dataToUpdate);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return back()->with('success', 'Order deleted successfully.');
    }

    public function generateOrderNumber()
    {
        $order_no = 600000;
        if (Order::get()->count() > 0) {
            $latestOrder = Order::withTrashed()->latest()->first();
            $order_no = intval(str_replace(['RA-', 'RAERR-'], '', $latestOrder->order_no)) + 1;
        }
        $generatedBid =  "RA-" . $order_no;
        $isExist = Order::where('order_no', $generatedBid)->first();
        if ($isExist) {
            $generatedBid = "RAERR-" . $order_no;
        }
        return $generatedBid;
    }
}
