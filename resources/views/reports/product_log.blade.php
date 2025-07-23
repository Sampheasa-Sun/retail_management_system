{{-- resources/views/reports/product_log.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h2 class="h4 mb-0">Product Activity Log</h2>
            <form action="{{ route('reports.product_log') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
                <input type="text" name="search" placeholder="Search product..." value="{{ $filters['search'] ?? '' }}" class="form-control form-control-sm" style="width: auto;">
                <input type="date" name="filter_date" value="{{ $filters['filter_date'] ?? '' }}" class="form-control form-control-sm" style="width: auto;">
                <select name="filter_action" class="form-select form-select-sm" style="width: auto;">
                    <option value="">All Actions</option>
                    <option value="Product Created" @selected(($filters['filter_action'] ?? '') == 'Product Created')>Product Created</option>
                    <option value="Stock Update" @selected(($filters['filter_action'] ?? '') == 'Stock Update')>Stock Update</option>
                    <option value="Status Change" @selected(($filters['filter_action'] ?? '') == 'Status Change')>Status Change</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('reports.product_log') }}" class="btn btn-secondary btn-sm">Clear</a>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product Name</th>
                        <th>Action</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->log_date->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $log->product->product_name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge 
                                    @if($log->action_type == 'Product Created') bg-success
                                    @elseif($log->action_type == 'Stock Update') bg-info
                                    @elseif($log->action_type == 'Status Change') bg-warning
                                    @else bg-secondary @endif">
                                    {{ $log->action_type }}
                                </span>
                            </td>
                            <td>{{ $log->details }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No product activity found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
