<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all products from the database, ordered by the correct column name.
        $products = Product::orderBy('product_name')->get();

        // Return the view and pass the products data to it.
        // The view is located at resources/views/products/index.blade.php
        return view('products.index', ['products' => $products]);
    }
}
