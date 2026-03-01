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
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
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

        // Verify Razorpay payment signature
        $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Payment verification failed: ' . $e->getMessage());
        }

        DB::transaction(function () use ($cartItems, $address, $request) {

            $total = 0;

            foreach ($cartItems as $item) {
                if ($item->quantity > $item->product->stock_quantity) {
                    throw new \Exception('Stock not sufficient for product: ' . $item->product->name);
                }

                $price = $item->product->discounted_price ?? $item->product->price;
                $total += $price * $item->quantity;
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'address_id' => $address->id,
                'total_amount' => $total,
                'status' => OrderStatus::PENDING,
                'payment_method' => 'Razorpay',
                'payment_status' => 'Paid',
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);

            foreach ($cartItems as $item) {
                $price = $item->product->discounted_price ?? $item->product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                ]);

                // Reduce stock
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            // Clear cart
            CartItem::where('user_id', auth()->id())->delete();
        });

        return redirect()->route('order.success')
            ->with('success', 'Order placed successfully!');
    }


    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', null);

        $orders = Order::with('user')->latest();

        if (!auth()->user()->is_admin) {
            $orders->where('user_id', auth()->id());
        }

        if (!empty($search)) {
            $orders->search($search);
        }

        if ($status !== null && $status !== '') {
            $orders->where('status', $status);
        }

        $orders = $orders->paginate(10)->withQueryString();

        return view(
            auth()->user()->is_admin
                ? 'orders.index'
                : 'customer-orders.index',
            [
                'orders' => $orders,
                'statuses' => OrderStatus::getAll(),
            ]
        );
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
            foreach ($order->orderItems as $item) {
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

    public function razorpay(Request $request)
    {
        $cartItems = CartItem::with('product')->where('user_id', auth()->id())->get();

        $total = 0;
        foreach ($cartItems as $item) {
            $price = $item->product->discounted_price ?? $item->product->price;
            $total += $price * $item->quantity;
        }

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        $razorpayOrder = $api->order->create([
            'receipt' => 'order_rcpt_' . time(),
            'amount' => intval($total * 100), // make sure integer paise
            'currency' => 'INR'
        ]);

        // convert SDK object to array
        return response()->json([
            'id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency'],
        ]);
    }
}
