<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('auth');
        $this->productService = $productService;
    }

    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'stock_quantity' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'description' => 'nullable|string|max:2000',
        ]);

        $this->productService->store($request->all());

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product added successfully');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string|max:2000',
        ]);

        $this->productService->update($product, $request->all());

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        // if ($product->images) {
        //     foreach ($product->images as $image) {
        //         \Storage::disk('public')->delete($image);
        //     }
        // }
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
