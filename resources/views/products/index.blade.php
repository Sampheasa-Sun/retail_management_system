@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Add Product Form (Collapsible) -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="h4 mb-0">
                <a class="d-block text-decoration-none text-dark" data-bs-toggle="collapse" href="#addProductCollapse" role="button" aria-expanded="false" aria-controls="addProductCollapse">
                    Add New Product
                </a>
            </h2>
        </div>
        <div class="collapse" id="addProductCollapse">
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" id="product_name" name="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name') }}" required>
                            @error('product_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Category</label>
                            <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="cost_price" class="form-label">Cost Price</label>
                            <input type="number" id="cost_price" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price') }}" step="0.01" required>
                            @error('cost_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <input type="number" id="selling_price" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{ old('selling_price') }}" step="0.01" required>
                             @error('selling_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="quantity_in_stock" class="form-label">Quantity in Stock</label>
                            <input type="number" id="quantity_in_stock" name="quantity_in_stock" class="form-control @error('quantity_in_stock') is-invalid @enderror" value="{{ old('quantity_in_stock') }}" required>
                            @error('quantity_in_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 mt-3">Add Product</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Active Product List -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Active Products</h2>
            <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary ms-2">Search</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activeProducts as $product)
                            <tr>
                                <td>{{ $product->product_name }}</td>
                                <td>
                                    {{ $product->category->category_name }}
                                    @if (!$product->category->is_active)
                                        <span class="badge bg-warning text-dark ms-1">Inactive Category</span>
                                    @endif
                                </td>
                                <td>{{ $product->quantity_in_stock }}</td>
                                <td class="text-end">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('products.toggleStatus', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning btn-sm">Deactivate</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">No active products found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Inactive Products Section -->
    <div class="card">
        <div class="card-header">
            <h2 class="h4 mb-0">
                 <a class="d-block text-decoration-none text-dark" data-bs-toggle="collapse" href="#inactiveProductsCollapse" role="button" aria-expanded="false" aria-controls="inactiveProductsCollapse">
                    Inactive Products ({{ $inactiveProducts->count() }})
                </a>
            </h2>
        </div>
        <div class="collapse" id="inactiveProductsCollapse">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inactiveProducts as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>
                                        {{ $product->category->category_name }}
                                        @if (!$product->category->is_active)
                                            <span class="badge bg-warning text-dark ms-1">Inactive Category</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('products.toggleStatus', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">Reactivate</button>
                                        </form>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('WARNING: This will permanently delete the product and all related records. Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">No inactive products found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
