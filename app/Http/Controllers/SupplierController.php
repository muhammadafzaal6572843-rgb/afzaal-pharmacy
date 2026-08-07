<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Purchase;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('purchases');
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        $suppliers = $query->latest()->paginate(15)->withQueryString();
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'company' => 'nullable|string|max:255',
            'status'  => 'required|in:active,inactive',
        ]);

        Supplier::create($data);
        return back()->with('success', 'Supplier added successfully.');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'company' => 'nullable|string|max:255',
            'status'  => 'required|in:active,inactive',
        ]);

        $supplier->update($data);
        return back()->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchases()->count() > 0) {
            return back()->with('error', 'Cannot delete supplier with purchase history.');
        }
        $supplier->delete();
        return back()->with('success', 'Supplier deleted.');
    }

    public function purchases(Supplier $supplier)
    {
        $purchases = $supplier->purchases()->with('user')->latest()->paginate(15);
        return view('suppliers.purchases', compact('supplier', 'purchases'));
    }
}
