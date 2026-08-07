@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
.stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 24px; }
.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(0,0,0,.3); }
.stat-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
}
.stat-card.indigo::before { background: linear-gradient(90deg, #6366f1, #818cf8); }
.stat-card.green::before  { background: linear-gradient(90deg, #10b981, #34d399); }
.stat-card.yellow::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
.stat-card.blue::before   { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
.stat-card.red::before    { background: linear-gradient(90deg, #ef4444, #f87171); }
.stat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
.stat-card.pink::before   { background: linear-gradient(90deg, #ec4899, #f472b6); }
.stat-card.teal::before   { background: linear-gradient(90deg, #14b8a6, #2dd4bf); }
.stat-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    margin-bottom: 16px;
}
.stat-card.indigo .stat-icon { background: rgba(99,102,241,.15); color: #818cf8; }
.stat-card.green .stat-icon  { background: rgba(16,185,129,.15);  color: #34d399; }
.stat-card.yellow .stat-icon { background: rgba(245,158,11,.15);  color: #fbbf24; }
.stat-card.blue .stat-icon   { background: rgba(59,130,246,.15);  color: #60a5fa; }
.stat-card.red .stat-icon    { background: rgba(239,68,68,.15);   color: #f87171; }
.stat-card.purple .stat-icon { background: rgba(139,92,246,.15);  color: #a78bfa; }
.stat-card.pink .stat-icon   { background: rgba(236,72,153,.15);  color: #f472b6; }
.stat-card.teal .stat-icon   { background: rgba(20,184,166,.15);  color: #2dd4bf; }
.stat-value { font-size: 28px; font-weight: 800; line-height: 1; }
.stat-label { font-size: 12.5px; color: var(--text-muted); margin-top: 6px; font-weight: 500; }
.stat-sub   { font-size: 11.5px; color: var(--text-muted); margin-top: 10px; display: flex; align-items: center; gap: 4px; }
.stat-up   { color: #34d399; }
.stat-down { color: #f87171; }
/* Bottom grid */
.bottom-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px; }
/* Chart */
.chart-bars { display: flex; align-items: flex-end; gap: 8px; height: 120px; }
.chart-bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; }
.chart-bar {
    width: 100%; border-radius: 6px 6px 0 0;
    background: linear-gradient(180deg, #6366f1, #4f46e5);
    min-height: 4px;
    transition: height .4s ease;
    position: relative;
}
.chart-bar:hover { background: linear-gradient(180deg, #818cf8, #6366f1); }
.chart-label { font-size: 10px; color: var(--text-muted); white-space: nowrap; }
/* Alert badges */
.alert-row { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
.alert-pill {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 14px; border-radius: 999px;
    font-size: 13px; font-weight: 600;
    text-decoration: none;
    transition: opacity .2s;
}
.alert-pill:hover { opacity: .85; }
.alert-pill.red    { background: rgba(239,68,68,.15); color: #f87171; border: 1px solid rgba(239,68,68,.25); }
.alert-pill.orange { background: rgba(245,158,11,.15); color: #fbbf24; border: 1px solid rgba(245,158,11,.25); }
.alert-pill.blue   { background: rgba(59,130,246,.15); color: #60a5fa; border: 1px solid rgba(59,130,246,.25); }
</style>
@endpush

@section('content')
<!-- Financial Summary Row (Daily, Monthly, Yearly) -->
<div style="margin-bottom:12px;font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.8px">
    <i class="fas fa-chart-pie" style="color:var(--primary);margin-right:6px"></i> Financial Performance (Sales & Profit)
</div>
<div class="stat-grid" style="margin-bottom:20px">
    <!-- Today (Daily) -->
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-value">₨{{ number_format($totalSalesToday, 0) }}</div>
        <div class="stat-label">Today's Sales</div>
        <div class="stat-sub" style="display:flex;align-items:center;justify-content:space-between;margin-top:6px;padding-top:6px;border-top:1px solid rgba(255,255,255,.08)">
            <span>Net Profit (Today):</span>
            <strong style="color:{{ $netProfitToday >= 0 ? '#34d399' : '#f87171' }}">₨{{ number_format($netProfitToday, 0) }}</strong>
        </div>
    </div>

    <!-- Monthly -->
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-value">₨{{ number_format($totalSalesMonth, 0) }}</div>
        <div class="stat-label">Monthly Revenue ({{ now()->format('M Y') }})</div>
        <div class="stat-sub" style="display:flex;align-items:center;justify-content:space-between;margin-top:6px;padding-top:6px;border-top:1px solid rgba(255,255,255,.08)">
            <span>Net Profit (Month):</span>
            <strong style="color:{{ $netProfitMonth >= 0 ? '#34d399' : '#f87171' }}">₨{{ number_format($netProfitMonth, 0) }}</strong>
        </div>
    </div>

    <!-- Yearly -->
    <div class="stat-card purple">
        <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-value">₨{{ number_format($totalSalesYear, 0) }}</div>
        <div class="stat-label">Yearly Revenue ({{ now()->format('Y') }})</div>
        <div class="stat-sub" style="display:flex;align-items:center;justify-content:space-between;margin-top:6px;padding-top:6px;border-top:1px solid rgba(255,255,255,.08)">
            <span>Net Profit (Year):</span>
            <strong style="color:{{ $netProfitYear >= 0 ? '#a78bfa' : '#f87171' }}">₨{{ number_format($netProfitYear, 0) }}</strong>
        </div>
    </div>

    <!-- Total Medicines / Stock -->
    <div class="stat-card indigo">
        <div class="stat-icon"><i class="fas fa-pills"></i></div>
        <div class="stat-value">{{ number_format($totalMedicines) }}</div>
        <div class="stat-label">Total Medicines</div>
        <div class="stat-sub"><i class="fas fa-box" style="font-size:10px"></i> {{ $todaySalesCount }} sales today</div>
    </div>
</div>

<!-- KPI Stats Row 2 -->
<div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-skull-crossbones"></i></div>
        <div class="stat-value">{{ $expiredCount }}</div>
        <div class="stat-label">Expired Medicines</div>
        <div class="stat-sub"><a href="{{ route('medicines.index', ['filter'=>'expired']) }}" style="color:#f87171">View all →</a></div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-value">{{ $lowStockCount }}</div>
        <div class="stat-label">Low Stock</div>
        <div class="stat-sub"><a href="{{ route('medicines.index', ['filter'=>'low_stock']) }}" style="color:#fbbf24">View all →</a></div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon"><i class="fas fa-industry"></i></div>
        <div class="stat-value">{{ $totalSuppliers }}</div>
        <div class="stat-label">Active Suppliers</div>
        <div class="stat-sub"><a href="{{ route('suppliers.index') }}" style="color:#a78bfa">Manage →</a></div>
    </div>
    <div class="stat-card pink">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-value">{{ $totalCustomers }}</div>
        <div class="stat-label">Total Customers</div>
        <div class="stat-sub"><a href="{{ route('customers.index') }}" style="color:#f472b6">Manage →</a></div>
    </div>
</div>

<!-- Bottom Grid -->
<div class="bottom-grid">
    <!-- Recent Sales -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-receipt" style="color:#6366f1;margin-right:8px"></i> Recent Sales</h3>
            <a href="{{ route('sales.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        @if($recentSales->isNotEmpty())
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSales as $sale)
                    <tr>
                        <td><span style="color:#a5b4fc;font-weight:600">{{ $sale->invoice_no }}</span></td>
                        <td>{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                        <td style="font-weight:700">₨{{ number_format($sale->grand_total, 2) }}</td>
                        <td>
                            <span class="badge {{ $sale->status === 'completed' ? 'badge-success' : ($sale->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </td>
                        <td style="color:var(--text-muted);font-size:12px">{{ $sale->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-shopping-cart"></i>
            <h3>No Sales Yet</h3>
            <p>Start making sales from the POS terminal</p>
            <a href="{{ route('pos.index') }}" class="btn btn-primary" style="margin-top:16px">Open POS</a>
        </div>
        @endif
    </div>

    <!-- Stock Alerts -->
    <div style="display:flex;flex-direction:column;gap:20px">
        <!-- 7 Day Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar" style="color:#10b981;margin-right:8px"></i> 7-Day Sales</h3>
            </div>
            @php
                $maxSale = $salesChart->max('sales') ?: 1;
            @endphp
            <div class="chart-bars">
                @foreach($salesChart as $day)
                <div class="chart-bar-wrap">
                    <div class="chart-bar" title="{{ $day['date'] }}: ₨{{ number_format($day['sales'],2) }}"
                         style="height: {{ max(4, ($day['sales'] / $maxSale) * 100) }}%"></div>
                    <div class="chart-label">{{ $day['date'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exclamation-triangle" style="color:#f59e0b;margin-right:8px"></i> Low Stock</h3>
                <a href="{{ route('medicines.index', ['filter'=>'low_stock']) }}" class="btn btn-outline btn-sm">All</a>
            </div>
            @if($lowStockAlerts->isNotEmpty())
            <div style="display:flex;flex-direction:column;gap:8px">
                @foreach($lowStockAlerts->take(5) as $med)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border)">
                    <div>
                        <div style="font-size:13px;font-weight:600">{{ $med->name }}</div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ $med->category?->name }}</div>
                    </div>
                    <span class="badge {{ $med->quantity == 0 ? 'badge-danger' : 'badge-warning' }}">
                        {{ $med->quantity }} {{ $med->unit }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <p style="color:var(--text-muted);font-size:13px;text-align:center;padding:20px">All medicines are well stocked ✓</p>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div style="margin-top:24px">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quick Actions</h3>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
            <a href="{{ route('pos.index') }}" class="btn btn-primary">
                <i class="fas fa-cash-register"></i> New Sale (POS)
            </a>
            <a href="{{ route('purchases.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Record Purchase
            </a>
            <a href="{{ route('medicines.create') }}" class="btn btn-warning">
                <i class="fas fa-pills"></i> Add Medicine
            </a>
            <a href="{{ route('reports.index') }}" class="btn btn-outline">
                <i class="fas fa-chart-bar"></i> View Reports
            </a>
        </div>
    </div>
</div>
@endsection
