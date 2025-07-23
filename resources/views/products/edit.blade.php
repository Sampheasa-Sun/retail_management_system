@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="h4 mb-0">Edit Product: {{ $product->product_name }}</h2>
                </div>
                <div class="card-body">
                    @if (!$product->category->is_active)
                        <div class="alert alert-warning" role="alert">
                            <strong>Warning:</strong> This product's category is inactive. You cannot restock it until the category is reactivated.
                        </div>
                    @endif

                    <form action="{{ route('products.update', $product) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" id="product_name" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}" required>
                            </div>
                            <div class="col-12">
                                <label for="category_id" class="form-label">Category</label>
                                <select id="category_id" name="category_id" class="form-select" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->category_id }}" {{ old('category_id', $product->category_id) == $category->category_id ? 'selected' : '' }}>
                                            {{ $category->category_name }} @if(!$category->is_active) (Inactive) @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="cost_price" class="form-label">Cost Price</label>
                                <input type="number" id="cost_price" name="cost_price" class="form-control" value="{{ old('cost_price', $product->cost_price) }}" step="0.01" required>
                            </div>
                            <div class="col-md-6">
                                <label for="selling_price" class="form-label">Selling Price</label>
                                <input type="number" id="selling_price" name="selling_price" class="form-control" value="{{ old('selling_price', $product->selling_price) }}" step="0.01" required>
                            </div>
                            <div class="col-12">
                                <label for="quantity_in_stock" class="form-label">Quantity in Stock</label>
                                <input type="number" id="quantity_in_stock" name="quantity_in_stock" class="form-control" value="{{ old('quantity_in_stock', $product->quantity_in_stock) }}" required 
                                    {{ !$product->category->is_active ? 'disabled' : '' }}>
                            </div>
                            <div class="col-12 d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
