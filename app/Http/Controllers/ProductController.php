<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     * Corresponds to manage_products.php main view.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Fetch active products
        $activeProducts = Product::with('category')
            ->where('is_active', true)
            ->when($search, function ($query, $search) {
                return $query->where('product_name', 'like', "%{$search}%");
            })
            ->get();

        // Fetch inactive products
        $inactiveProducts = Product::with('category')
            ->where('is_active', false)
            ->when($search, function ($query, $search) {
                return $query->where('product_name', 'like', "%{$search}%");
            })
            ->get();
            
        // Fetch active categories for the "Add Product" form
        $categories = Category::where('is_active', true)->orderBy('category_name')->get();

        return view('products.index', compact('activeProducts', 'inactiveProducts', 'categories', 'search'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity_in_stock' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::create($validated);
            ProductLog::create([
                'product_id' => $product->product_id,
                'action_type' => 'Product Created',
                'details' => 'Product was created with stock of ' . $validated['quantity_in_stock'],
            ]);
        });

        return redirect()->route('products.index')->with('success', 'Product added successfully.');
    }

    /**
     * Show the form for editing the specified product.
     * Corresponds to edit_product.php
     */
    public function edit(Product $product)
    {
        // Load the product with its category relationship
        $product->load('category');

        // Fetch active categories, but also include the product's current category even if it's inactive
        $categories = Category::where('is_active', true)
            ->orWhere('category_id', $product->category_id)
            ->orderBy('category_name')
            ->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity_in_stock' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated, $product) {
            $oldStock = $product->quantity_in_stock;
            $product->update($validated);

            if ($oldStock != $validated['quantity_in_stock']) {
                ProductLog::create([
                    'product_id' => $product->product_id,
                    'action_type' => 'Stock Update',
                    'details' => 'Stock changed from ' . $oldStock . ' to ' . $validated['quantity_in_stock'],
                ]);
            }
        });

        return redirect()->route('products.edit', $product)->with('success', 'Product updated successfully.');
    }

    /**
     * Toggle the active status of the specified product.
     */
    public function toggleStatus(Product $product)
    {
        // Prevent reactivation if the category is inactive
        if (!$product->is_active && !$product->category->is_active) {
            return redirect()->route('products.index')->with('error', 'Cannot reactivate product because its category is inactive.');
        }

        $product->is_active = !$product->is_active;
        $product->save();

        ProductLog::create([
            'product_id' => $product->product_id,
            'action_type' => 'Status Change',
            'details' => 'Status changed to ' . ($product->is_active ? 'Active' : 'Inactive'),
        ]);

        return redirect()->route('products.index')->with('success', 'Product status updated.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Using a transaction to ensure all related data is deleted together
        DB::transaction(function () use ($product) {
            ProductLog::where('product_id', $product->product_id)->delete();
            // Assuming a 'saleDetails' relationship on the Product model
            $product->saleDetails()->delete();
            $product->delete();
        });

        return redirect()->route('products.index')->with('success', 'Product permanently deleted.');
    }
}
