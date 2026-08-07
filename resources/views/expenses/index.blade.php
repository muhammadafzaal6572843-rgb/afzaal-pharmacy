@extends('layouts.app')
@section('title', 'Expenses')

@section('content')
<div class="page-header">
    <div>
        <h2>Expense Management</h2>
        <p>Track operational expenses and costs</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addExpenseModal')">
        <i class="fas fa-plus"></i> Add Expense
    </button>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom:20px">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap">
        <div class="search-bar" style="flex:2;min-width:200px">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Search expenses..." value="{{ request('search') }}">
        </div>
        <select name="category" class="form-control" style="flex:1;min-width:160px">
            <option value="">All Categories</option>
            @foreach(['general','utilities','salaries','maintenance','rent','supplies','other'] as $cat)
            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" class="form-control" style="flex:1;min-width:140px" value="{{ request('date_from') }}">
        <input type="date" name="date_to" class="form-control" style="flex:1;min-width:140px" value="{{ request('date_to') }}">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Title</th><th>Category</th><th>Amount</th><th>Date</th><th>Recorded By</th><th>Description</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($expenses as $exp)
                <tr>
                    <td style="color:var(--text-muted)">{{ $loop->iteration }}</td>
                    <td style="font-weight:600">{{ $exp->title }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($exp->category) }}</span></td>
                    <td style="font-weight:800;color:#f87171">₨{{ number_format($exp->amount, 2) }}</td>
                    <td>{{ $exp->expense_date->format('M d, Y') }}</td>
                    <td style="color:var(--text-muted)">{{ $exp->user?->name }}</td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ Str::limit($exp->description, 40) ?? '-' }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-outline btn-sm btn-icon"
                                onclick="editExpense({{ $exp->id }}, '{{ addslashes($exp->title) }}', '{{ $exp->category }}', {{ $exp->amount }}, '{{ $exp->expense_date->format('Y-m-d') }}', '{{ addslashes($exp->description ?? '') }}')"
                                title="Edit"><i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('expenses.destroy', $exp) }}" onsubmit="return confirm('Delete this expense?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><i class="fas fa-wallet"></i><h3>No Expenses</h3><p>Record your operational expenses</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px">{{ $expenses->links('vendor.pagination.custom') }}</div>
</div>

<!-- Add Expense Modal -->
<div class="modal-overlay" id="addExpenseModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add Expense</h3>
            <button class="modal-close" onclick="closeModal('addExpenseModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('expenses.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Electricity Bill" required>
                </div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-control" required>
                            @foreach(['general','utilities','salaries','maintenance','rent','supplies','other'] as $cat)
                            <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Amount (₨) *</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Date *</label>
                    <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Optional details..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addExpenseModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Expense Modal -->
<div class="modal-overlay" id="editExpenseModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Expense</h3>
            <button class="modal-close" onclick="closeModal('editExpenseModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" id="editExpenseForm">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" id="expTitle" class="form-control" required>
                </div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category" id="expCat" class="form-control" required>
                            @foreach(['general','utilities','salaries','maintenance','rent','supplies','other'] as $cat)
                            <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Amount (₨) *</label>
                        <input type="number" name="amount" id="expAmount" class="form-control" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Date *</label>
                    <input type="date" name="expense_date" id="expDate" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="expDesc" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editExpenseModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editExpense(id, title, cat, amount, date, desc) {
    document.getElementById('expTitle').value = title;
    document.getElementById('expCat').value = cat;
    document.getElementById('expAmount').value = amount;
    document.getElementById('expDate').value = date;
    document.getElementById('expDesc').value = desc;
    document.getElementById('editExpenseForm').action = '/expenses/' + id;
    openModal('editExpenseModal');
}
</script>
@endpush
