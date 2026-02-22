<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Carbon;
use App\Models\User;

class DummyOrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $user = User::where('email', 'user@gmail.com')->first();

        if (!$user) return;

        for ($i = 1; $i <= 12; $i++) {

            $status = collect(OrderStatus::getAll())->random();

            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $user->addresses()->inRandomOrder()->first()->id,
                'total_amount' => 0,
                'status' => $status,
                'delivered_at' => $status === OrderStatus::COMPLETED
                    ? Carbon::now()->subDays(rand(1, 10))
                    : null,
            ]);

            $totalAmount = 0;

            $randomProducts = $products->random(2);

            foreach ($randomProducts as $product) {

                $quantity = rand(1, 3);
                $price = $product->discounted_price ?? $product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $price,
                    'quantity' => $quantity,
                ]);

                $totalAmount += $price * $quantity;
            }

            $order->update([
                'total_amount' => $totalAmount
            ]);
        }
    }
}
