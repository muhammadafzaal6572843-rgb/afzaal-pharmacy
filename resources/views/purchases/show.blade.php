@extends('layouts.app')
@section('title', 'Purchase #' . $purchase->invoice_no)

@section('content')
<div class="page-header">
    <div>
        <h2>Purchase Receipt</h2>
        <p>Invoice: {{ $purchase->invoice_no }}</p>
    </div>
    <div style="display:flex;gap:10px">
        <button onclick="window.print()" class="btn btn-outline"><i class="fas fa-print"></i> Print</button>
        <a href="{{ route('purchases.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<div class="card">
    <div style="display:flex;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:16px">
        <div>
            <h3 style="font-size:20px;font-weight:800;margin-bottom:4px">Purchase Receipt</h3>
            <p style="color:var(--text-muted)">Invoice: <strong style="color:#a5b4fc">{{ $purchase->invoice_no }}</strong></p>
        </div>
        <div style="text-align:right">
            <p style="font-size:13px;color:var(--text-muted)">Date: {{ $purchase->purchase_date->format('M d, Y') }}</p>
            <p style="font-size:13px;color:var(--text-muted)">Recorded by: {{ $purchase->user?->name }}</p>
        </div>
    </div>

    <!-- Supplier Info -->
    <div style="background:var(--bg-card2);border:1px solid var(--border);border-radius:12px;padding:16px;margin-bottom:24px">
        <div style="font-size:11px;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Supplier</div>
        <div style="font-size:16px;font-weight:700">{{ $purchase->supplier?->name }}</div>
        @if($purchase->supplier?->company)<div style="font-size:13px;color:var(--text-muted)">{{ $purchase->supplier->company }}</div>@endif
        @if($purchase->supplier?->phone)<div style="font-size:13px;color:var(--text-muted)">{{ $purchase->supplier->phone }}</div>@endif
    </div>

    <!-- Items Table -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Medicine</th><th>Category</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div style="font-weight:600">{{ $item->medicine?->name }}</div>
                        @if($item->medicine?->manufacturer_name)
                        <div style="font-size:11px;color:var(--text-muted)">{{ $item->medicine->manufacturer_name }}</div>
                        @endif
                    </td>
                    <td>{{ $item->medicine?->category?->name ?? '-' }}</td>
                    <td>{{ $item->quantity }} {{ $item->medicine?->unit }}</td>
                    <td>₨{{ number_format($item->purchase_price, 2) }}</td>
                    <td style="font-weight:700">₨{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Total -->
    <div style="margin-top:24px;display:flex;justify-content:flex-end">
        <div style="background:linear-gradient(135deg,rgba(99,102,241,.15),rgba(16,185,129,.1));border:1px solid rgba(99,102,241,.3);border-radius:16px;padding:20px;min-width:280px">
            <div style="display:flex;justify-content:space-between;font-size:20px;font-weight:800">
                <span>Grand Total</span>
                <span style="color:#34d399">₨{{ number_format($purchase->grand_total, 2) }}</span>
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:6px">{{ $purchase->items->count() }} medicine(s) received</div>
        </div>
    </div>

    @if($purchase->notes)
    <div style="margin-top:20px;padding:14px;background:var(--bg-card2);border-radius:10px;font-size:13px;color:var(--text-muted)">
        <strong>Notes:</strong> {{ $purchase->notes }}
    </div>
    @endif
</div>
@endsection
