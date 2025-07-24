<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SaleOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the appropriate dashboard based on user role.
     */
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            
            // --- Data for Stat Boxes ---
            $totalProducts = Product::count();
            $totalEmployees = User::where('role', 'employee')->count();
            // Specify the table for 'created_at' to be safe
            $todaysRevenue = SaleOrder::whereDate('sales_order.created_at', Carbon::today())->sum('total');

            // --- Data for Employee Performance Chart (Last 30 Days) ---
            // The fix is here: we specify 'sales_order.created_at' to resolve the ambiguity
            $salesData = SaleOrder::where('sales_order.created_at', '>=', Carbon::now()->subDays(30))
                ->join('users', 'sales_order.user_id', '=', 'users.id')
                ->where('users.role', 'employee') // Only include employees
                ->select('users.name as employee_name', DB::raw('SUM(sales_order.total) as total_sales'))
                ->groupBy('users.name')
                ->orderBy('total_sales', 'desc')
                ->get();

            // Prepare the data for Chart.js
            $chartLabels = $salesData->pluck('employee_name');
            $chartData = $salesData->pluck('total_sales');

            // Return the admin dashboard view with all the data
            return view('admin.dashboard', [
                'totalProducts' => $totalProducts,
                'totalEmployees' => $totalEmployees,
                'todaysRevenue' => $todaysRevenue,
                'chartLabels' => $chartLabels,
                'chartData' => $chartData,
            ]);

        } else {
            // If not an admin, show the standard employee dashboard
            return view('dashboard');
        }
    }
}
