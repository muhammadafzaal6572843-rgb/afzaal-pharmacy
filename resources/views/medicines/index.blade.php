@extends('layouts.app')
@section('title', 'Medicines')

@section('content')
<div class="page-header">
    <div>
        <h2>Medicine Inventory</h2>
        <p>Manage all medicines and stock levels</p>
    </div>
    <a href="{{ route('medicines.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Medicine
    </a>
</div>

<!-- Filter Pills -->
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap">
    <a href="{{ route('medicines.index') }}" class="btn {{ !request('filter') ? 'btn-primary' : 'btn-outline' }} btn-sm">All</a>
    <a href="{{ route('medicines.index', ['filter'=>'low_stock']) }}" class="btn {{ request('filter')=='low_stock' ? 'btn-warning' : 'btn-outline' }} btn-sm">
        <i class="fas fa-exclamation-triangle"></i> Low Stock ({{ $lowStockCount }})
    </a>
    <a href="{{ route('medicines.index', ['filter'=>'expired']) }}" class="btn {{ request('filter')=='expired' ? 'btn-danger' : 'btn-outline' }} btn-sm">
        <i class="fas fa-skull-crossbones"></i> Expired ({{ $expiredCount }})
    </a>
    <a href="{{ route('medicines.index', ['filter'=>'out_of_stock']) }}" class="btn {{ request('filter')=='out_of_stock' ? 'btn-danger' : 'btn-outline' }} btn-sm">
        <i class="fas fa-times-circle"></i> Out of Stock ({{ $outOfStockCount }})
    </a>
</div>

<!-- Search/Filter -->
<div class="card" style="margin-bottom:20px">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap">
        <div class="search-bar" style="flex:2">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Search by name, barcode, manufacturer..." value="{{ request('search') }}">
        </div>
        <select name="category" class="form-control" style="flex:1;min-width:180px">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="hidden" name="filter" value="{{ request('filter') }}">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('medicines.index') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Medicine</th>
                    <th>Category</th>
                    <th>Barcode</th>
                    <th>Batch No.</th>
                    <th>Stock</th>
                    <th>Purchase Price</th>
                    <th>Sale Price</th>
                    <th>Expiry</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $med)
                <tr>
                    <td style="color:var(--text-muted)">{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:600">{{ $med->name }}</div>
                        @if($med->manufacturer_name)
                        <div style="font-size:11px;color:var(--text-muted)">{{ $med->manufacturer_name }}</div>
                        @endif
                    </td>
                    <td><span class="badge badge-info">{{ $med->category?->name ?? '-' }}</span></td>
                    <td>
                        @if($med->barcode)
                        <span style="font-family:monospace;font-size:12px;color:#a5b4fc">{{ $med->barcode }}</span>
                        @else <span style="color:var(--text-muted)">-</span> @endif
                    </td>
                    <td>
                        @if($med->batch_no)
                        <span class="badge badge-secondary" style="font-family:monospace;font-size:11px">{{ $med->batch_no }}</span>
                        @else <span style="color:var(--text-muted)">-</span> @endif
                    </td>
                    <td>
                        @if($med->quantity == 0)
                            <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Out of Stock</span>
                        @elseif($med->is_low_stock)
                            <span class="badge badge-warning"><i class="fas fa-exclamation"></i> {{ $med->quantity }} {{ $med->unit }}</span>
                        @else
                            <span class="badge badge-success"><i class="fas fa-check"></i> {{ $med->quantity }} {{ $med->unit }}</span>
                        @endif
                    </td>
                    <td style="font-weight:600">₨{{ number_format($med->purchase_price, 2) }}</td>
                    <td style="font-weight:600;color:#34d399">₨{{ number_format($med->sale_price, 2) }}</td>
                    <td>
                        @if($med->expiry_date)
                            @if($med->is_expired)
                                <span class="badge badge-danger"><i class="fas fa-skull-crossbones"></i> {{ $med->expiry_date->format('M Y') }}</span>
                            @elseif($med->expiry_status === 'expiring_soon')
                                <span class="badge badge-warning"><i class="fas fa-clock"></i> {{ $med->expiry_date->format('M Y') }}</span>
                            @else
                                <span class="badge badge-success">{{ $med->expiry_date->format('M Y') }}</span>
                            @endif
                        @else <span style="color:var(--text-muted)">N/A</span> @endif
                    </td>
                    <td>
                        <span class="badge {{ $med->status === 'active' ? 'badge-success' : 'badge-gray' }}">{{ ucfirst($med->status) }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('medicines.edit', $med) }}" class="btn btn-outline btn-sm btn-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('medicines.destroy', $med) }}" onsubmit="return confirm('Delete this medicine?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10">
                        <div class="empty-state">
                            <i class="fas fa-pills"></i>
                            <h3>No Medicines Found</h3>
                            <p>{{ request('search') ? 'Try a different search term' : 'Add your first medicine to get started' }}</p>
                            <a href="{{ route('medicines.create') }}" class="btn btn-primary" style="margin-top:16px">Add Medicine</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px">{{ $medicines->links('vendor.pagination.custom') }}</div>
</div>
@endsection
