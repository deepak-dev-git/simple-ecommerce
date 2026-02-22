<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class DummyProductSeeder extends Seeder
{
    public function run(): void
    {
        $availableImages = [];

        // image list: products/1.jpg to products/16.jpg that i manually added in folder public/products
        for ($i = 1; $i <= 16; $i++) {
            $availableImages[] = "products/{$i}.jpg";
        }

        for ($i = 1; $i <= 16; $i++) {

            $price = rand(500, 5000);
            $discount = rand(0, 30);

            $discountedPrice = $price;

            if ($discount > 0) {
                $discountedPrice = $price - ($price * $discount / 100);
            }

            // 2 random images
            $images = collect($availableImages)
                        ->random(2)
                        ->values()
                        ->toArray();

            Product::create([
                'user_id' => 1, // admin
                'name' => 'Sample Product ' . $i,
                'price' => $price,
                'stock_quantity' => rand(10, 100),
                'discount' => $discount,
                'discounted_price' => $discountedPrice,
                'images' => $images,
                'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum",
            ]);
        }
    }
}
