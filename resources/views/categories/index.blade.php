@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Add Category Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h2 class="h4 mb-0">Add New Category</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf {{-- CSRF protection token --}}
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" id="category_name" name="category_name" class="form-control @error('category_name') is-invalid @enderror" value="{{ old('category_name') }}" required>
                        @error('category_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Category</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Category List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0">Category List</h2>
                <form action="{{ route('categories.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control" placeholder="Search categories..." value="{{ $search_keyword ?? '' }}">
                    <button type="submit" class="btn btn-primary ms-2">Search</button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category Name</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td>{{ $category->category_id }}</td>
                                    <td>{{ $category->category_name }}</td>
                                    <td>
                                        @if ($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            {{-- Toggle Status Form --}}
                                            <form action="{{ route('categories.toggleStatus', $category) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $category->is_active ? 'btn-warning' : 'btn-success' }}">
                                                    {{ $category->is_active ? 'Set Inactive' : 'Set Active' }}
                                                </button>
                                            </form>
                                            {{-- Delete Form --}}
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No categories found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
