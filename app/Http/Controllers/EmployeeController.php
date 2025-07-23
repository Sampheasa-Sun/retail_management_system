<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     * Corresponds to the main view in manage_employees.php
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        // Handle search functionality for name or ID
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('employee_id', 'like', '%' . $searchTerm . '%')
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        $employees = $query->orderBy('employee_id', 'asc')->get();

        return view('employees.index', [
            'employees' => $employees,
            'search_keyword' => $request->input('search', '')
        ]);
    }

    /**
     * Store a newly created employee in storage.
     * Corresponds to the 'add_employee' POST logic.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }

    /**
     * Toggle the active status of the specified employee.
     * Corresponds to the 'toggle_status' POST logic.
     */
    public function toggleStatus(Employee $employee)
    {
        $employee->is_active = !$employee->is_active;
        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Employee status updated successfully.');
    }

    /**
     * Remove the specified employee from storage.
     * Corresponds to the 'delete_employee' POST logic.
     */
    public function destroy(Employee $employee)
    {
        // Check if the employee has associated sales records.
        // This assumes a 'sales' relationship is defined in the Employee model.
        if ($employee->sales()->exists()) {
            return redirect()->route('employees.index')->with('error', 'Cannot delete employee. They have existing sales records. Please set them to inactive instead.');
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee has been permanently deleted.');
    }
}
