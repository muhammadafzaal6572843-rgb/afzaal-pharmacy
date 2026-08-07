<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'user']);
        if ($request->filled('search')) {
            $query->where('invoice_no', 'like', '%' . $request->search . '%')
                  ->orWhereHas('supplier', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }
        $purchases = $query->latest()->paginate(15)->withQueryString();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();
        $medicines = Medicine::active()->notExpired()->orderBy('name')->get();
        return view('purchases.create', compact('suppliers', 'medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'    => 'required|exists:suppliers,id',
            'purchase_date'  => 'required|date',
            'items'          => 'required|array|min:1',
            'items.*.medicine_id'    => 'required|exists:medicines,id',
            'items.*.quantity'       => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $setting    = \App\Models\Setting::get();
            $invoiceNo  = $setting->invoice_prefix . '-PUR-' . strtoupper(uniqid());

            $grandTotal = 0;
            foreach ($request->items as $item) {
                $grandTotal += $item['quantity'] * $item['purchase_price'];
            }

            $purchase = Purchase::create([
                'supplier_id'   => $request->supplier_id,
                'user_id'       => auth()->id(),
                'invoice_no'    => $invoiceNo,
                'purchase_date' => $request->purchase_date,
                'grand_total'   => $grandTotal,
                'notes'         => $request->notes,
            ]);

            foreach ($request->items as $item) {
                PurchaseItem::create([
                    'purchase_id'    => $purchase->id,
                    'medicine_id'    => $item['medicine_id'],
                    'quantity'       => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'subtotal'       => $item['quantity'] * $item['purchase_price'],
                ]);

                // Auto-increment stock
                Medicine::find($item['medicine_id'])->increment('quantity', $item['quantity']);
            }

            DB::commit();
            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Purchase recorded successfully. Stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save purchase: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.medicine']);
        return view('purchases.show', compact('purchase'));
    }
}
