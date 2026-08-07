@extends('layouts.app')
@section('title', 'Customers')

@section('content')
<div class="page-header">
    <div>
        <h2>Customers</h2>
        <p>Manage customer profiles and credit balances</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addCustomerModal')">
        <i class="fas fa-plus"></i> Add Customer
    </button>
</div>

<div class="card" style="margin-bottom:20px">
    <form method="GET" style="display:flex;gap:12px">
        <div class="search-bar" style="flex:1">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Search name, phone, email..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{ route('customers.index') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Credit Limit</th>
                    <th>Credit Balance</th>
                    <th>Sales</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $cust)
                <tr>
                    <td style="color:var(--text-muted)">{{ $loop->iteration }}</td>
                    <td style="font-weight:600">{{ $cust->name }}</td>
                    <td>{{ $cust->phone ?? '-' }}</td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $cust->email ?? '-' }}</td>
                    <td>₨{{ number_format($cust->credit_limit, 2) }}</td>
                    <td>
                        @if($cust->credit_balance > 0)
                            <span class="badge badge-warning">₨{{ number_format($cust->credit_balance, 2) }}</span>
                        @else
                            <span class="badge badge-success">₨0.00</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('customers.sales', $cust) }}" class="badge badge-info" style="text-decoration:none">
                            {{ $cust->sales_count }} sales
                        </a>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-outline btn-sm btn-icon"
                                onclick="editCustomer({{ $cust->id }}, '{{ addslashes($cust->name) }}', '{{ $cust->phone }}', '{{ $cust->email }}', '{{ addslashes($cust->address ?? '') }}', {{ $cust->credit_limit }})"
                                title="Edit"><i class="fas fa-edit"></i>
                            </button>
                            <a href="{{ route('customers.sales', $cust) }}" class="btn btn-outline btn-sm btn-icon" title="Sales"><i class="fas fa-shopping-cart"></i></a>
                            <form method="POST" action="{{ route('customers.destroy', $cust) }}" onsubmit="return confirm('Delete this customer?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><i class="fas fa-users"></i><h3>No Customers</h3><p>Add your first customer</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px">{{ $customers->links('vendor.pagination.custom') }}</div>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="addCustomerModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add Customer</h3>
            <button class="modal-close" onclick="closeModal('addCustomerModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('customers.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                <div class="grid grid-2">
                    <div class="form-group"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
                </div>
                <div class="form-group"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
                <div class="form-group">
                    <label class="form-label">Credit Limit (₨)</label>
                    <input type="number" name="credit_limit" class="form-control" value="0" step="0.01">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addCustomerModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editCustomerModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Customer</h3>
            <button class="modal-close" onclick="closeModal('editCustomerModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" id="editCustomerForm">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Name *</label><input type="text" name="name" id="custName" class="form-control" required></div>
                <div class="grid grid-2">
                    <div class="form-group"><label class="form-label">Phone</label><input type="text" name="phone" id="custPhone" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" id="custEmail" class="form-control"></div>
                </div>
                <div class="form-group"><label class="form-label">Address</label><textarea name="address" id="custAddress" class="form-control" rows="2"></textarea></div>
                <div class="form-group">
                    <label class="form-label">Credit Limit (₨)</label>
                    <input type="number" name="credit_limit" id="custCredit" class="form-control" step="0.01">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editCustomerModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editCustomer(id, name, phone, email, address, credit) {
    document.getElementById('custName').value = name;
    document.getElementById('custPhone').value = phone;
    document.getElementById('custEmail').value = email;
    document.getElementById('custAddress').value = address;
    document.getElementById('custCredit').value = credit;
    document.getElementById('editCustomerForm').action = '/customers/' + id;
    openModal('editCustomerModal');
}
</script>
@endpush
