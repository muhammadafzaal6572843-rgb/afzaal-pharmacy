@extends('layouts.app')
@section('title', $supplier->name . ' - Purchases')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $supplier->name }}</h2>
        <p>Purchase history for this supplier</p>
    </div>
    <a href="{{ route('suppliers.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Invoice</th><th>Date</th><th>Items</th><th>Total</th><th>Recorded By</th><th>Action</th></tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                <tr>
                    <td style="color:#a5b4fc;font-weight:600">{{ $purchase->invoice_no }}</td>
                    <td>{{ $purchase->purchase_date->format('M d, Y') }}</td>
                    <td><span class="badge badge-info">{{ $purchase->items_count ?? '-' }}</span></td>
                    <td style="font-weight:700">₨{{ number_format($purchase->grand_total, 2) }}</td>
                    <td>{{ $purchase->user?->name }}</td>
                    <td><a href="{{ route('purchases.show', $purchase) }}" class="btn btn-outline btn-sm">View</a></td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-truck"></i><h3>No Purchases</h3><p>No purchases recorded for this supplier.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px">{{ $purchases->links('vendor.pagination.custom') }}</div>
</div>
@endsection
