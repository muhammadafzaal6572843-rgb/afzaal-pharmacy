@extends('layouts.app')
@section('title', 'Sales History')

@section('content')
<div class="page-header">
    <div>
        <h2>Sales History</h2>
        <p>All completed sales transactions</p>
    </div>
    <a href="{{ route('pos.index') }}" class="btn btn-primary"><i class="fas fa-cash-register"></i> New Sale (POS)</a>
</div>

<div class="card" style="margin-bottom:20px">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap">
        <div class="search-bar" style="flex:2;min-width:200px">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Search invoice number..." value="{{ request('search') }}">
        </div>
        <input type="date" name="date_from" class="form-control" style="flex:1;min-width:150px" value="{{ request('date_from') }}">
        <input type="date" name="date_to" class="form-control" style="flex:1;min-width:150px" value="{{ request('date_to') }}">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('sales.index') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Invoice</th><th>Customer</th><th>Cashier</th><th>Total</th><th>Paid</th><th>Due</th><th>Method</th><th>Status</th><th>Date</th><th>Action</th></tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td style="color:#a5b4fc;font-weight:600">{{ $sale->invoice_no }}</td>
                    <td>{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                    <td style="color:var(--text-muted)">{{ $sale->user?->name }}</td>
                    <td style="font-weight:700">₨{{ number_format($sale->grand_total, 2) }}</td>
                    <td style="color:#34d399">₨{{ number_format($sale->paid_amount, 2) }}</td>
                    <td style="color:{{ $sale->due_amount > 0 ? '#f59e0b' : 'var(--text-muted)' }}">₨{{ number_format($sale->due_amount, 2) }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($sale->payment_method) }}</span></td>
                    <td><span class="badge {{ $sale->status === 'completed' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($sale->status) }}</span></td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $sale->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('pos.invoice', $sale) }}" class="btn btn-outline btn-sm" target="_blank">
                            <i class="fas fa-file-invoice"></i> Invoice
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10"><div class="empty-state"><i class="fas fa-shopping-cart"></i><h3>No Sales</h3><p>Make your first sale from the POS</p><a href="{{ route('pos.index') }}" class="btn btn-primary" style="margin-top:16px">Open POS</a></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px">{{ $sales->links('vendor.pagination.custom') }}</div>
</div>
@endsection
