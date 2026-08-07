<?php

namespace App\Http\Controllers;

use App\Models\MedicineCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = MedicineCategory::withCount('medicines')->latest()->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:medicine_categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        MedicineCategory::create($data);
        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, MedicineCategory $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:medicine_categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
        ]);

        $category->update($data);
        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(MedicineCategory $category)
    {
        if ($category->medicines()->count() > 0) {
            return back()->with('error', 'Cannot delete category with medicines.');
        }
        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }
}
