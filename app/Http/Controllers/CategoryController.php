<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     * Corresponds to the main view in manage_categories.php
     */
    public function index(Request $request)
    {
        // Start with the base query for categories
        $query = Category::query();

        // Handle the search functionality
        if ($request->filled('search')) {
            $query->where('category_name', 'like', '%' . $request->input('search') . '%');
        }

        // Fetch the categories, ordered by their ID
        $categories = $query->orderBy('category_id', 'asc')->get();

        // Return the view, passing the categories data to it
        return view('categories.index', [
            'categories' => $categories,
            'search_keyword' => $request->input('search', '') // For displaying the search term in the view
        ]);
    }

    /**
     * Store a newly created category in the database.
     * Corresponds to the 'add_category' POST logic in manage_categories.php
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'category_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'category_name') // Ensure category name is unique
            ],
        ]);

        // Create and save the new category
        Category::create([
            'category_name' => $validated['category_name'],
            'is_active' => true, // Default to active
        ]);

        // Redirect back to the category list with a success message
        return redirect()->route('categories.index')->with('success', 'Category added successfully.');
    }

    /**
     * Toggle the active status of the specified category.
     * Corresponds to the 'toggle_status' POST logic in manage_categories.php
     */
    public function toggleStatus(Category $category)
    {
        // Toggle the is_active boolean field
        $category->is_active = !$category->is_active;
        $category->save();

        // Redirect back with a success message
        return redirect()->route('categories.index')->with('success', 'Category status updated successfully.');
    }

    /**
     * Remove the specified category from the database.
     * Corresponds to the 'delete_category' POST logic in manage_categories.php
     */
    public function destroy(Category $category)
    {
        // Check if the category is assigned to any products.
        // The 'products' relationship needs to be defined in the Category model.
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Cannot delete category. It is currently assigned to one or more products.');
        }

        // If no products are assigned, delete the category
        $category->delete();

        // Redirect back with a success message
        return redirect()->route('categories.index')->with('success', 'Category has been permanently deleted.');
    }
}
