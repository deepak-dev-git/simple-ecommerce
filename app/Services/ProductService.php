<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function store(array $data)
    {
        $imagePaths = $this->handleImages($data);

        $discountedPrice = $this->calculateDiscount(
            $data['price'],
            $data['discount'] ?? 0
        );

        return Product::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'price' => $data['price'],
            'images' => $imagePaths,
            'stock_quantity' => $data['stock_quantity'],
            'discount' => $data['discount'] ?? 0,
            'discounted_price' => $discountedPrice,
            'description' => $data['description'] ?? null,
        ]);
    }

    public function update(Product $product, array $data)
    {
        $imagePaths = $product->images ?? [];

        if (!empty($data['images'])) {
            foreach ($data['images'] as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        $discountedPrice = $this->calculateDiscount(
            $data['price'],
            $data['discount'] ?? 0
        );

        return $product->update([
            'name' => $data['name'],
            'price' => $data['price'],
            'stock_quantity' => $data['stock_quantity'],
            'discount' => $data['discount'] ?? 0,
            'discounted_price' => $discountedPrice,
            'images' => $imagePaths,
            'description' => $data['description'] ?? null,
        ]);
    }

    private function handleImages(array $data)
    {
        $imagePaths = [];

        if (!empty($data['images'])) {
            foreach ($data['images'] as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        return $imagePaths;
    }

    private function calculateDiscount($price, $discount)
    {
        if ($discount > 0) {
            return $price - ($price * $discount / 100);
        }

        return $price;
    }
}
