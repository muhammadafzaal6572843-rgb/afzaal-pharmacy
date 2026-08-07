<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('barcode', 'like', '%' . $request->search . '%')
                  ->orWhere('manufacturer_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filter === 'low_stock') {
            $query->lowStock();
        } elseif ($request->filter === 'expired') {
            $query->expired();
        } elseif ($request->filter === 'expiring_soon') {
            $query->expiringSoon(30);
        } elseif ($request->filter === 'out_of_stock') {
            $query->outOfStock();
        }

        $medicines   = $query->latest()->paginate(15)->withQueryString();
        $categories  = MedicineCategory::orderBy('name')->get();
        $lowStockCount   = Medicine::active()->lowStock()->count();
        $expiredCount    = Medicine::active()->expired()->count();
        $outOfStockCount = Medicine::active()->outOfStock()->count();

        return view('medicines.index', compact('medicines', 'categories', 'lowStockCount', 'expiredCount', 'outOfStockCount'));
    }

    public function create()
    {
        $categories = MedicineCategory::orderBy('name')->get();
        return view('medicines.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'       => 'required|exists:medicine_categories,id',
            'name'              => 'required|string|max:255',
            'manufacturer_name' => 'nullable|string|max:255',
            'barcode'           => 'nullable|string|unique:medicines,barcode',
            'batch_no'          => 'nullable|string|max:100',
            'unit'              => 'required|string|max:50',
            'purchase_price'    => 'required|numeric|min:0',
            'sale_price'        => 'required|numeric|min:0',
            'quantity'          => 'required|integer|min:0',
            'expiry_date'       => 'nullable|date',
            'reorder_level'     => 'required|integer|min:0',
            'description'       => 'nullable|string|max:500',
            'status'            => 'required|in:active,inactive',
        ]);

        Medicine::create($data);
        return redirect()->route('medicines.index')->with('success', 'Medicine added successfully.');
    }

    public function edit(Medicine $medicine)
    {
        $categories = MedicineCategory::orderBy('name')->get();
        return view('medicines.edit', compact('medicine', 'categories'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $data = $request->validate([
            'category_id'       => 'required|exists:medicine_categories,id',
            'name'              => 'required|string|max:255',
            'manufacturer_name' => 'nullable|string|max:255',
            'barcode'           => 'nullable|string|unique:medicines,barcode,' . $medicine->id,
            'batch_no'          => 'nullable|string|max:100',
            'unit'              => 'required|string|max:50',
            'purchase_price'    => 'required|numeric|min:0',
            'sale_price'        => 'required|numeric|min:0',
            'quantity'          => 'required|integer|min:0',
            'expiry_date'       => 'nullable|date',
            'reorder_level'     => 'required|integer|min:0',
            'description'       => 'nullable|string|max:500',
            'status'            => 'required|in:active,inactive',
        ]);

        $medicine->update($data);
        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return back()->with('success', 'Medicine deleted.');
    }
}
