@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Add Employee Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h2 class="h4 mb-0">Add New Employee</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('employees.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" id="position" name="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position') }}" required>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Employee</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Employee List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0">Employee List</h2>
                <form action="{{ route('employees.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or ID..." value="{{ $search_keyword ?? '' }}">
                    <button type="submit" class="btn btn-primary ms-2">Search</button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->employee_id }}</td>
                                    <td>{{ $employee->full_name }}</td>
                                    <td>{{ $employee->position }}</td>
                                    <td>
                                        <span class="badge {{ $employee->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $employee->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <form action="{{ route('employees.toggleStatus', $employee) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $employee->is_active ? 'btn-warning' : 'btn-success' }}">
                                                    {{ $employee->is_active ? 'Set Inactive' : 'Set Active' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Are you sure you want to PERMANENTLY delete this employee?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No employees found.</td>
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
