<?php

namespace App\Http\Controllers;

use App\Models\SaleOrder;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the sales for the admin report.
     */
    public function index()
    {
        // Fetch all sales orders, ordered by the newest first.
        // Eager load the 'user' relationship to prevent N+1 query problems.
        $sales = SaleOrder::with('user')->latest()->get();

        // The view file is located at resources/views/sales/index.blade.php
        return view('sales.index', ['sales' => $sales]);
    }

    /**
     * Show the form for creating a new sale (for employees).
     */
    public function create()
    {
        // The view file is located at resources/views/sales/create.blade.php
        return view('sales.create');
    }

    /**
     * Store a newly created sale in storage.
     * We will build the logic for this in the next step.
     */
    public function store(Request $request)
    {
        // Logic to save the sale will go here.
    }
}
