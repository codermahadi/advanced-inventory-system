<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
            ]);

            Category::create($validated);

            return redirect()
                ->route('categories.index')
                ->with('status', 'Category created successfully.');
        } catch (\Exception $e) {
            \Log::error('Category creation failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create category. ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified category.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(Request $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
            ]);

            $category->update($validated);

            return redirect()
                ->route('categories.index')
                ->with('status', 'Category updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Category update failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update category. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified category from the database.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);

            // Check if category has any products
            if ($category->products()->exists()) {
                return back()->withErrors(['error' => 'Cannot delete category that has products.']);
            }

            $category->delete();

            return redirect()
                ->route('categories.index')
                ->with('status', 'Category deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Category deletion failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete category. ' . $e->getMessage()]);
        }
    }
}
