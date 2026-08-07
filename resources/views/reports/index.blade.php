@extends('layouts.app')
@section('title', 'Reports')

@push('styles')
<style>
.report-nav { display:flex; gap:10px; margin-bottom:24px; flex-wrap:wrap; }
.report-tab { padding:10px 20px; border-radius:10px; font-size:13.5px; font-weight:600; cursor:pointer; border:1px solid var(--border); color:var(--text-muted); background:var(--bg-card); text-decoration:none; transition:all .2s; }
.report-tab.active, .report-tab:hover { background:var(--primary); color:#fff; border-color:var(--primary); }
.metric-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
.metric-box { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; padding:20px; text-align:center; }
.metric-box .val { font-size:24px; font-weight:800; }
.metric-box .lbl { font-size:12px; color:var(--text-muted); margin-top:6px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h2>Reports & Analytics</h2>
        <p>Business performance and financial overview</p>
    </div>
    <button onclick="window.print()" class="btn btn-outline"><i class="fas fa-print"></i> Print</button>
</div>

<!-- Date Filter & Quick Presets -->
<div class="card" style="margin-bottom:20px">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid var(--border)">
        <div style="font-size:13px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px">
            <i class="fas fa-filter" style="color:var(--primary);margin-right:6px"></i> Quick Date Filter
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="{{ route('reports.index', array_merge(request()->all(), ['preset'=>'today', 'type'=>$reportType])) }}"
               class="btn btn-sm {{ ($preset === 'today') ? 'btn-primary' : 'btn-outline' }}">
                📅 Today (Daily)
            </a>
            <a href="{{ route('reports.index', array_merge(request()->all(), ['preset'=>'month', 'type'=>$reportType])) }}"
               class="btn btn-sm {{ ($preset === 'month' || (!$preset && !request('date_from'))) ? 'btn-primary' : 'btn-outline' }}">
                📅 This Month
            </a>
            <a href="{{ route('reports.index', array_merge(request()->all(), ['preset'=>'year', 'type'=>$reportType])) }}"
               class="btn btn-sm {{ ($preset === 'year') ? 'btn-primary' : 'btn-outline' }}">
                📅 This Year
            </a>
        </div>
    </div>

    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
        <div class="form-group" style="margin:0;flex:1;min-width:150px">
            <label class="form-label">From Date</label>
            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom->format('Y-m-d') }}">
        </div>
        <div class="form-group" style="margin:0;flex:1;min-width:150px">
            <label class="form-label">To Date</label>
            <input type="date" name="date_to" class="form-control" value="{{ $dateTo->format('Y-m-d') }}">
        </div>
        <input type="hidden" name="type" id="reportType" value="{{ $reportType }}">
        <button type="submit" class="btn btn-primary">Custom Filter</button>
        <a href="{{ route('reports.index') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<!-- Report Tabs -->
<div class="report-nav">
    <a href="{{ route('reports.index', array_merge(request()->all(), ['type'=>'summary'])) }}" class="report-tab {{ $reportType == 'summary' ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Summary</a>
    <a href="{{ route('reports.index', array_merge(request()->all(), ['type'=>'sales'])) }}"   class="report-tab {{ $reportType == 'sales'   ? 'active' : '' }}"><i class="fas fa-shopping-cart"></i> Sales</a>
    <a href="{{ route('reports.index', array_merge(request()->all(), ['type'=>'purchases'])) }}" class="report-tab {{ $reportType == 'purchases' ? 'active' : '' }}"><i class="fas fa-truck"></i> Purchases</a>
    <a href="{{ route('reports.index', array_merge(request()->all(), ['type'=>'expenses'])) }}"  class="report-tab {{ $reportType == 'expenses'  ? 'active' : '' }}"><i class="fas fa-wallet"></i> Expenses</a>
    <a href="{{ route('reports.index', array_merge(request()->all(), ['type'=>'stock'])) }}"     class="report-tab {{ $reportType == 'stock'     ? 'active' : '' }}"><i class="fas fa-boxes"></i> Stock</a>
    <a href="{{ route('reports.index', array_merge(request()->all(), ['type'=>'top_selling'])) }}" class="report-tab {{ $reportType == 'top_selling' ? 'active' : '' }}"><i class="fas fa-star"></i> Top Selling</a>
</div>

<!-- KPI Cards -->
<div class="metric-grid">
    <div class="metric-box">
        <div class="val" style="color:#34d399">₨{{ number_format($totalSales, 0) }}</div>
        <div class="lbl"><i class="fas fa-shopping-cart"></i> Sales Revenue ({{ $totalSalesCount }} txns)</div>
    </div>
    <div class="metric-box">
        <div class="val" style="color:#fbbf24">₨{{ number_format($grossProfit, 0) }}</div>
        <div class="lbl"><i class="fas fa-box-open"></i> Gross Profit (Sales - Cost)</div>
    </div>
    <div class="metric-box">
        <div class="val" style="color:#f87171">₨{{ number_format($totalExpenses, 0) }}</div>
        <div class="lbl"><i class="fas fa-wallet"></i> Total Expenses</div>
    </div>
    <div class="metric-box">
        <div class="val" style="color:{{ $netProfit >= 0 ? '#34d399' : '#f87171' }}">
            @if($netProfit < 0)-@endif₨{{ number_format(abs($netProfit), 0) }}
        </div>
        <div class="lbl">
            <i class="fas fa-chart-line"></i>
            {{ $netProfit >= 0 ? 'Net Profit' : 'Net Loss' }}
        </div>
    </div>
</div>

<!-- Report Content -->
@if($reportType === 'summary')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calculator" style="color:#6366f1;margin-right:8px"></i> Profit &amp; Loss Statement</h3>
        <span style="font-size:12px;color:var(--text-muted)">{{ $dateFrom->format('M d, Y') }} — {{ $dateTo->format('M d, Y') }}</span>
    </div>
    <div style="max-width:550px">
        <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border);font-size:14px">
            <span>➕ Total Sales Revenue</span>
            <span style="font-weight:700;color:#34d399">+₨{{ number_format($totalSales, 2) }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border);font-size:14px">
            <span>➖ Cost of Goods Sold (Medicine Cost)</span>
            <span style="font-weight:700;color:#f87171">-₨{{ number_format($cogs, 2) }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border);font-size:14px;background:rgba(99,102,241,.06);padding-left:8px;padding-right:8px;border-radius:6px;margin:6px 0">
            <span style="font-weight:600;color:#818cf8">📊 Gross Profit (Sales - Cost)</span>
            <span style="font-weight:800;color:#818cf8">₨{{ number_format($grossProfit, 2) }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border);font-size:14px">
            <span>➖ Operating Expenses</span>
            <span style="font-weight:700;color:#f87171">-₨{{ number_format($totalExpenses, 2) }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:16px 8px;font-size:18px;font-weight:800;border-top:2px solid var(--border);margin-top:6px;background:rgba(16,185,129,.08);border-radius:8px">
            <span>{{ $netProfit >= 0 ? '💰 NET PROFIT' : '⚠️ NET LOSS' }}</span>
            <span style="color:{{ $netProfit >= 0 ? '#34d399' : '#f87171' }}">
                ₨{{ number_format(abs($netProfit), 2) }}
            </span>
        </div>
    </div>
</div>

@elseif($reportType === 'sales')
<div class="card">
    <div class="card-header"><h3 class="card-title">Sales Transactions</h3></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Invoice</th><th>Customer</th><th>Cashier</th><th>Total</th><th>Paid</th><th>Due</th><th>Method</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td style="color:#a5b4fc;font-weight:600">{{ $sale->invoice_no }}</td>
                    <td>{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                    <td style="color:var(--text-muted)">{{ $sale->user?->name }}</td>
                    <td style="font-weight:700">₨{{ number_format($sale->grand_total, 2) }}</td>
                    <td style="color:#34d399">₨{{ number_format($sale->paid_amount, 2) }}</td>
                    <td style="color:#f59e0b">₨{{ number_format($sale->due_amount, 2) }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($sale->payment_method) }}</span></td>
                    <td><span class="badge {{ $sale->status === 'completed' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($sale->status) }}</span></td>
                    <td style="font-size:12px">{{ $sale->created_at->format('M d, Y') }}</td>
                    <td><a href="{{ route('pos.invoice', $sale) }}" class="btn btn-outline btn-sm" target="_blank">Invoice</a></td>
                </tr>
                @empty
                <tr><td colspan="10"><div class="empty-state"><i class="fas fa-shopping-cart"></i><h3>No Sales</h3></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px">{{ $sales->links('vendor.pagination.custom') }}</div>
</div>

@elseif($reportType === 'purchases')
<div class="card">
    <div class="card-header"><h3 class="card-title">Purchase Transactions</h3></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Invoice</th><th>Supplier</th><th>Date</th><th>Total</th><th>Recorded By</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($purchases as $pur)
                <tr>
                    <td style="color:#a5b4fc;font-weight:600">{{ $pur->invoice_no }}</td>
                    <td style="font-weight:600">{{ $pur->supplier?->name }}</td>
                    <td>{{ $pur->purchase_date->format('M d, Y') }}</td>
                    <td style="font-weight:700">₨{{ number_format($pur->grand_total, 2) }}</td>
                    <td style="color:var(--text-muted)">{{ $pur->user?->name }}</td>
                    <td><a href="{{ route('purchases.show', $pur) }}" class="btn btn-outline btn-sm">View</a></td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-truck"></i><h3>No Purchases</h3></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px">{{ $purchases->links('vendor.pagination.custom') }}</div>
</div>

@elseif($reportType === 'expenses')
<div class="card">
    <div class="card-header"><h3 class="card-title">Expense Report</h3></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Title</th><th>Category</th><th>Amount</th><th>Date</th><th>Recorded By</th></tr></thead>
            <tbody>
                @forelse($expenses as $exp)
                <tr>
                    <td style="font-weight:600">{{ $exp->title }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($exp->category) }}</span></td>
                    <td style="color:#f87171;font-weight:700">₨{{ number_format($exp->amount, 2) }}</td>
                    <td>{{ $exp->expense_date->format('M d, Y') }}</td>
                    <td style="color:var(--text-muted)">{{ $exp->user?->name }}</td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-wallet"></i><h3>No Expenses</h3></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px">{{ $expenses->links('vendor.pagination.custom') }}</div>
</div>

@elseif($reportType === 'stock')
<div class="card">
    <div class="card-header"><h3 class="card-title">Stock Report</h3></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Medicine</th><th>Category</th><th>Stock</th><th>Reorder Level</th><th>Purchase Price</th><th>Sale Price</th><th>Stock Value</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($stockReport as $med)
                <tr>
                    <td style="font-weight:600">{{ $med->name }}</td>
                    <td>{{ $med->category?->name }}</td>
                    <td>{{ $med->quantity }} {{ $med->unit }}</td>
                    <td style="color:var(--text-muted)">{{ $med->reorder_level }}</td>
                    <td>₨{{ number_format($med->purchase_price, 2) }}</td>
                    <td>₨{{ number_format($med->sale_price, 2) }}</td>
                    <td style="font-weight:700">₨{{ number_format($med->quantity * $med->purchase_price, 2) }}</td>
                    <td>
                        @if($med->quantity == 0)
                            <span class="badge badge-danger">Out of Stock</span>
                        @elseif($med->is_low_stock)
                            <span class="badge badge-warning">Low Stock</span>
                        @else
                            <span class="badge badge-success">Good</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><i class="fas fa-boxes"></i><h3>No Medicines</h3></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px">{{ $stockReport->links('vendor.pagination.custom') }}</div>
</div>

@elseif($reportType === 'top_selling')
<div class="card">
    <div class="card-header"><h3 class="card-title">Top Selling Medicines</h3></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Rank</th><th>Medicine</th><th>Units Sold</th><th>Revenue</th></tr></thead>
            <tbody>
                @forelse($topSelling as $i => $item)
                <tr>
                    <td>
                        @if($i == 0) <span style="font-size:20px">🥇</span>
                        @elseif($i == 1) <span style="font-size:20px">🥈</span>
                        @elseif($i == 2) <span style="font-size:20px">🥉</span>
                        @else <span style="color:var(--text-muted);font-weight:700">#{{ $i + 1 }}</span>
                        @endif
                    </td>
                    <td style="font-weight:600">{{ $item->medicine?->name }}</td>
                    <td><span class="badge badge-success">{{ $item->total_qty }} units</span></td>
                    <td style="font-weight:800;color:#34d399">₨{{ number_format($item->total_revenue, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="4"><div class="empty-state"><i class="fas fa-star"></i><h3>No Data</h3></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
