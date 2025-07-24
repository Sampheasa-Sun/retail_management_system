<?php

namespace App\Http\Controllers;

use App\Models\ProductLog;
use App\Models\User;
use Illuminate\Http\Request;

class ProductLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with a query builder instance for ProductLog
        $query = ProductLog::with(['user', 'product'])->latest();

        // Filter by change type if a type is provided in the request
        if ($request->filled('change_type')) {
            $query->where('change_type', $request->change_type);
        }

        // Filter by employee if a user_id is provided
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Paginate the results to keep the page fast
        $logs = $query->paginate(15);

        // Get all employees to populate the filter dropdown
        $employees = User::where('role', 'employee')->orderBy('name')->get();

        // Return the view and pass the logs and employees data to it
        return view('product-logs.index', [
            'logs' => $logs,
            'employees' => $employees,
        ]);
    }
}
