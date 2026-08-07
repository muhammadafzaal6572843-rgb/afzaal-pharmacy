<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Medicine;
use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name')->get();
        $setting   = Setting::get();
        return view('pos.index', compact('customers', 'setting'));
    }

    /**
     * Live search for POS - returns JSON
     */
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $medicines = Medicine::active()
            ->notExpired()
            ->where('quantity', '>', 0)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                      ->orWhere('barcode', 'like', '%' . $q . '%')
                      ->orWhere('manufacturer_name', 'like', '%' . $q . '%');
            })
            ->with('category')
            ->take(20)
            ->get(['id', 'name', 'barcode', 'sale_price', 'quantity', 'unit', 'category_id', 'manufacturer_name']);

        return response()->json($medicines);
    }

    /**
     * Process and save a sale
     */
    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.medicine_id'=> 'required|exists:medicines,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'discount'           => 'nullable|numeric|min:0',
            'paid_amount'        => 'required|numeric|min:0',
            'payment_method'     => 'required|in:cash,card,credit',
            'customer_id'        => 'nullable|exists:customers,id',
        ]);

        DB::beginTransaction();
        try {
            $setting  = Setting::get();
            $subtotal = 0;
            $itemsToProcess = [];

            // Secure server-side validation & price calculation with row lock
            foreach ($request->items as $itemData) {
                $medicine = Medicine::where('id', $itemData['medicine_id'])->lockForUpdate()->first();
                if (!$medicine || $medicine->status !== 'active') {
                    DB::rollBack();
                    return back()->with('error', "Medicine not found or inactive.");
                }
                if ($medicine->is_expired) {
                    DB::rollBack();
                    return back()->with('error', "Medicine '{$medicine->name}' is expired and cannot be sold.");
                }
                if ($medicine->quantity < $itemData['quantity']) {
                    DB::rollBack();
                    return back()->with('error', "Insufficient stock for '{$medicine->name}'. Available: {$medicine->quantity} {$medicine->unit}.");
                }

                $itemPrice = $medicine->sale_price; // Always trust DB price over client input
                $itemSubtotal = $itemData['quantity'] * $itemPrice;
                $subtotal += $itemSubtotal;

                $itemsToProcess[] = [
                    'medicine'   => $medicine,
                    'quantity'   => $itemData['quantity'],
                    'sale_price' => $itemPrice,
                    'subtotal'   => $itemSubtotal,
                ];
            }

            $discountRate = (float)($setting->default_discount ?? 0);
            $discount     = $subtotal * ($discountRate / 100);
            $taxRate      = (float)($setting->tax ?? 0);
            $taxAmount    = ($subtotal - $discount) * ($taxRate / 100);
            $grandTotal   = max(0, $subtotal - $discount + $taxAmount);

            $paidAmount = (float)$request->paid_amount;
            if ($request->payment_method === 'credit') {
                $paidAmount = 0;
            }
            $dueAmount = max(0, $grandTotal - $paidAmount);

            $invoiceNo  = $setting->invoice_prefix . '-' . date('Ymd') . '-' . str_pad(Sale::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $sale = Sale::create([
                'customer_id'    => $request->customer_id,
                'user_id'        => auth()->id(),
                'invoice_no'     => $invoiceNo,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'tax'            => $taxAmount,
                'grand_total'    => $grandTotal,
                'paid_amount'    => $paidAmount,
                'due_amount'     => $dueAmount,
                'payment_method' => $request->payment_method,
                'status'         => $dueAmount > 0 ? 'pending' : 'completed',
                'notes'          => $request->notes,
            ]);

            foreach ($itemsToProcess as $processed) {
                SaleItem::create([
                    'sale_id'     => $sale->id,
                    'medicine_id' => $processed['medicine']->id,
                    'quantity'    => $processed['quantity'],
                    'sale_price'  => $processed['sale_price'],
                    'subtotal'    => $processed['subtotal'],
                ]);

                // Safely decrement stock
                $processed['medicine']->decrement('quantity', $processed['quantity']);
            }

            // Update customer credit balance if partial/unpaid
            if ($dueAmount > 0 && $request->customer_id) {
                Customer::find($request->customer_id)->increment('credit_balance', $dueAmount);
            }

            DB::commit();
            return redirect()->route('pos.invoice', $sale)
                ->with('success', 'Sale completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Sale failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Print invoice for a sale
     */
    public function invoice(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.medicine']);
        $setting = Setting::get();
        return view('sales.invoice', compact('sale', 'setting'));
    }

    /**
     * List all sales
     */
    public function salesIndex(Request $request)
    {
        $query = Sale::with(['customer', 'user']);
        if ($request->filled('search')) {
            $query->where('invoice_no', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        $sales = $query->latest()->paginate(15)->withQueryString();
        return view('sales.index', compact('sales'));
    }
}
