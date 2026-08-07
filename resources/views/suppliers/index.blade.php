@extends('layouts.app')
@section('title', 'Suppliers')

@section('content')
<div class="page-header">
    <div>
        <h2>Suppliers</h2>
        <p>Manage your medicine suppliers and vendors</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addSupplierModal')">
        <i class="fas fa-plus"></i> Add Supplier
    </button>
</div>

<!-- Search -->
<div class="card" style="margin-bottom:20px">
    <form method="GET" style="display:flex;gap:12px">
        <div class="search-bar" style="flex:1">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Search by name, phone, email..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Supplier</th>
                    <th>Company</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Purchases</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $sup)
                <tr>
                    <td style="color:var(--text-muted)">{{ $loop->iteration }}</td>
                    <td style="font-weight:600">{{ $sup->name }}</td>
                    <td style="color:var(--text-muted)">{{ $sup->company ?? '-' }}</td>
                    <td>{{ $sup->phone ?? '-' }}</td>
                    <td style="color:var(--text-muted);font-size:12px">{{ $sup->email ?? '-' }}</td>
                    <td>
                        <a href="{{ route('suppliers.purchases', $sup) }}" class="badge badge-info" style="text-decoration:none">
                            {{ $sup->purchases_count }} purchases
                        </a>
                    </td>
                    <td><span class="badge {{ $sup->status === 'active' ? 'badge-success' : 'badge-gray' }}">{{ ucfirst($sup->status) }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-outline btn-sm btn-icon"
                                onclick="editSupplier({{ $sup->id }}, '{{ addslashes($sup->name) }}', '{{ $sup->phone }}', '{{ $sup->email }}', '{{ addslashes($sup->address ?? '') }}', '{{ addslashes($sup->company ?? '') }}', '{{ $sup->status }}')"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="{{ route('suppliers.purchases', $sup) }}" class="btn btn-outline btn-sm btn-icon" title="View Purchases">
                                <i class="fas fa-truck"></i>
                            </a>
                            <form method="POST" action="{{ route('suppliers.destroy', $sup) }}" onsubmit="return confirm('Delete this supplier?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><i class="fas fa-industry"></i><h3>No Suppliers</h3><p>Add your first supplier</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px">{{ $suppliers->links('vendor.pagination.custom') }}</div>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="addSupplierModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add Supplier</h3>
            <button class="modal-close" onclick="closeModal('addSupplierModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('suppliers.store') }}">
            @csrf
            <div class="modal-body">
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Company</label>
                        <input type="text" name="company" class="form-control">
                    </div>
                </div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addSupplierModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editSupplierModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Supplier</h3>
            <button class="modal-close" onclick="closeModal('editSupplierModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" id="editSupplierForm">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" id="supName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Company</label>
                        <input type="text" name="company" id="supCompany" class="form-control">
                    </div>
                </div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" id="supPhone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="supEmail" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" id="supAddress" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="supStatus" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editSupplierModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editSupplier(id, name, phone, email, address, company, status) {
    document.getElementById('supName').value = name;
    document.getElementById('supPhone').value = phone;
    document.getElementById('supEmail').value = email;
    document.getElementById('supAddress').value = address;
    document.getElementById('supCompany').value = company;
    document.getElementById('supStatus').value = status;
    document.getElementById('editSupplierForm').action = '/suppliers/' + id;
    openModal('editSupplierModal');
}
</script>
@endpush
