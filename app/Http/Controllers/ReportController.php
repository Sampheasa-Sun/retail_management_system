<?php

namespace App\Http\Controllers;

use App\Models\ProductLog;
use App\Models\SaleOrder;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display the sales report.
     * Corresponds to reports.php
     */
    public function salesReport(Request $request)
    {
        $query = SaleOrder::with(['employee', 'details.product'])
            ->orderBy('sale_date', 'desc');

        // Handle date filtering
        if ($request->filled('filter_date')) {
            $query->whereDate('sale_date', $request->input('filter_date'));
        }

        $sales = $query->get();

        // Grouping data for the view can be complex.
        // This is a simplified version. A more advanced approach might use custom collections.
        $reports = $sales->groupBy('employee.full_name')->map(function ($employeeSales) {
            return $employeeSales->groupBy(function ($sale) {
                return $sale->sale_date->format('Y-m-d');
            });
        });

        return view('reports.sales', [
            'reports' => $reports,
            'filter_date' => $request->input('filter_date', '')
        ]);
    }

    /**
     * Display the product activity log.
     * Corresponds to product_log_report.php
     */
    public function productLog(Request $request)
    {
        $query = ProductLog::with('product')->orderBy('log_date', 'desc');

        // Handle filters
        if ($request->filled('filter_date')) {
            $query->whereDate('log_date', $request->input('filter_date'));
        }
        if ($request->filled('filter_action')) {
            $query->where('action_type', $request->input('filter_action'));
        }
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->whereHas('product', function ($q) use ($searchTerm) {
                $q->where('product_name', 'like', '%' . $searchTerm . '%');
            });
        }

        $logs = $query->get();

        return view('reports.product_log', [
            'logs' => $logs,
            'filters' => $request->only(['filter_date', 'filter_action', 'search'])
        ]);
    }
}
