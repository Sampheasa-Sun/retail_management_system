{{-- resources/views/reports/sales.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Sales Reports by Employee</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('reports.sales') }}" method="GET" class="d-flex align-items-center">
                <div class="me-2">
                    <label for="filter_date" class="form-label me-2">Filter by Date:</label>
                    <input type="date" id="filter_date" name="filter_date" value="{{ $filter_date }}" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('reports.sales') }}" class="btn btn-secondary ms-2">Clear</a>
            </form>
        </div>
    </div>
</div>

<div class="accordion" id="salesReportAccordion">
    @forelse ($reports as $employeeName => $days)
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-{{ Str::slug($employeeName) }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($employeeName) }}" aria-expanded="false" aria-controls="collapse-{{ Str::slug($employeeName) }}">
                    {{ $employeeName }}
                </button>
            </h2>
            <div id="collapse-{{ Str::slug($employeeName) }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ Str::slug($employeeName) }}" data-bs-parent="#salesReportAccordion">
                <div class="accordion-body">
                    @foreach ($days as $date => $sales)
                        <div class="card mb-3">
                            <div class="card-header">{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</div>
                            <div class="card-body">
                                @foreach ($sales as $sale)
                                    <div class="border-bottom pb-2 mb-2">
                                        <div class="d-flex justify-content-between">
                                            <strong>Sale #{{ $sale->sales_id }} <small class="text-muted">{{ $sale->sale_date->format('g:i A') }}</small></strong>
                                            <strong>Total: ${{ number_format($sale->total_amount, 2) }}</strong>
                                        </div>
                                        <ul>
                                            @foreach ($sale->details as $detail)
                                                <li>
                                                    {{ $detail->product->product_name }} (x{{ $detail->quantity }})
                                                    @if ($detail->discount_amount > 0)
                                                        <span class="text-danger">- ${{ number_format($detail->discount_amount * $detail->quantity, 2) }} discount</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center">
                No sales data found for the selected date.
            </div>
        </div>
    @endforelse
</div>
@endsection