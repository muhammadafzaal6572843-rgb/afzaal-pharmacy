@extends('layouts.app')
@section('title', 'Purchases')

@section('content')
<div class="page-header">
    <div>
        <h2>Purchase Records</h2>
        <p>All stock purchase transactions</p>
    </div>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Purchase
    </a>
</div>

<div class="card" style="margin-bottom:20px">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap">
        <div class="search-bar" style="flex:2;min-width:200px">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Search invoice or supplier..." value="{{ request('search') }}">
        </div>
        <input type="date" name="date_from" class="form-control" style="flex:1;min-width:150px" value="{{ request('date_from') }}" placeholder="From">
        <input type="date" name="date_to" class="form-control" style="flex:1;min-width:150px" value="{{ request('date_to') }}" placeholder="To">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('purchases.index') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Invoice</th><th>Supplier</th><th>Date</th><th>Items</th><th>Total</th><th>Recorded By</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                <tr>
                    <td style="color:#a5b4fc;font-weight:600">{{ $purchase->invoice_no }}</td>
                    <td style="font-weight:600">{{ $purchase->supplier?->name }}</td>
                    <td>{{ $purchase->purchase_date->format('M d, Y') }}</td>
                    <td><span class="badge badge-info">{{ $purchase->items->count() ?? 0 }}</span></td>
                    <td style="font-weight:700">₨{{ number_format($purchase->grand_total, 2) }}</td>
                    <td style="color:var(--text-muted)">{{ $purchase->user?->name }}</td>
                    <td>
                        <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-truck"></i><h3>No Purchases</h3><p>Record your first purchase</p><a href="{{ route('purchases.create') }}" class="btn btn-primary" style="margin-top:16px">New Purchase</a></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px">{{ $purchases->links('vendor.pagination.custom') }}</div>
</div>
@endsection
