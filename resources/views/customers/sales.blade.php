@extends('layouts.app')
@section('title', $customer->name . ' - Sales')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $customer->name }}</h2>
        <p>Sales history and credit balance</p>
    </div>
    <a href="{{ route('customers.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="grid grid-3" style="margin-bottom:20px">
    <div class="card" style="text-align:center">
        <div style="font-size:24px;font-weight:800;color:#34d399">₨{{ number_format($customer->credit_limit, 2) }}</div>
        <div style="color:var(--text-muted);font-size:13px;margin-top:4px">Credit Limit</div>
    </div>
    <div class="card" style="text-align:center">
        <div style="font-size:24px;font-weight:800;color:{{ $customer->credit_balance > 0 ? '#f59e0b' : '#34d399' }}">₨{{ number_format($customer->credit_balance, 2) }}</div>
        <div style="color:var(--text-muted);font-size:13px;margin-top:4px">Outstanding Balance</div>
    </div>
    <div class="card" style="text-align:center">
        <div style="font-size:24px;font-weight:800;color:#60a5fa">{{ $sales->total() }}</div>
        <div style="color:var(--text-muted);font-size:13px;margin-top:4px">Total Purchases</div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Invoice</th><th>Date</th><th>Total</th><th>Paid</th><th>Due</th><th>Method</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td style="color:#a5b4fc;font-weight:600">{{ $sale->invoice_no }}</td>
                    <td>{{ $sale->created_at->format('M d, Y') }}</td>
                    <td style="font-weight:700">₨{{ number_format($sale->grand_total, 2) }}</td>
                    <td style="color:#34d399">₨{{ number_format($sale->paid_amount, 2) }}</td>
                    <td style="color:{{ $sale->due_amount > 0 ? '#f59e0b' : 'var(--text-muted)' }}">₨{{ number_format($sale->due_amount, 2) }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($sale->payment_method) }}</span></td>
                    <td><span class="badge {{ $sale->status === 'completed' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($sale->status) }}</span></td>
                    <td><a href="{{ route('pos.invoice', $sale) }}" class="btn btn-outline btn-sm" target="_blank">Invoice</a></td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><i class="fas fa-shopping-cart"></i><h3>No Sales</h3></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px">{{ $sales->links('vendor.pagination.custom') }}</div>
</div>
@endsection
