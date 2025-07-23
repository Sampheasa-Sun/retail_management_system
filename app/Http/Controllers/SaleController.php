<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Show the form for creating a new sale.
     * Corresponds to index.php
     */
    public function create()
    {
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        
        // Fetch products that are active and have stock
        $products = Product::where('is_active', true)
            ->where('quantity_in_stock', '>', 0)
            ->orderBy('product_name')
            ->get();

        return view('sales.create', compact('employees', 'products'));
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'sale_date' => 'nullable|date',
            'products_json' => 'required|json',
        ]);

        try {
            // Use the database transaction to ensure data integrity
            DB::transaction(function () use ($validated) {
                $saleDate = $validated['sale_date'] ?? now();
                $products = json_decode($validated['products_json'], true);

                // Laravel's equivalent of calling the stored procedure.
                // This logic should be moved into a dedicated "Service Class" in a larger application.
                $totalAmount = 0;
                foreach ($products as $item) {
                    $product = Product::find($item['product_id']);
                    $discountAmount = $product->selling_price * ($item['discount_percentage'] / 100);
                    $priceAtSale = $product->selling_price - $discountAmount;
                    $totalAmount += $priceAtSale * $item['quantity'];
                }

                $saleOrder = DB::table('sales_order')->insertGetId([
                    'employee_id' => $validated['employee_id'],
                    'sale_date' => $saleDate,
                    'total_amount' => $totalAmount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($products as $item) {
                    $product = Product::find($item['product_id']);
                    if ($product->quantity_in_stock < $item['quantity']) {
                        // This check prevents selling more than is in stock.
                        throw new \Exception('Insufficient stock for product: ' . $product->product_name);
                    }
                    $discountAmount = $product->selling_price * ($item['discount_percentage'] / 100);

                    DB::table('sale_order_detail')->insert([
                        'sales_id' => $saleOrder,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price_at_sale' => $product->selling_price,
                        'discount_amount' => $discountAmount,
                    ]);

                    $product->decrement('quantity_in_stock', $item['quantity']);
                }
            });
            
            return response()->json(['status' => 'success', 'message' => 'Success! New sale has been recorded.']);

        } catch (\Exception $e) {
            // Return a JSON error response
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }
}
