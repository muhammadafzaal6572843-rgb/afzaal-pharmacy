@extends('layouts.app')
@section('title', 'Categories')

@section('content')
<div class="page-header">
    <div>
        <h2>Medicine Categories</h2>
        <p>Manage medicine classification groups</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addCategoryModal')">
        <i class="fas fa-plus"></i> Add Category
    </button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Medicines</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td style="color:var(--text-muted)">{{ $loop->iteration }}</td>
                    <td style="font-weight:600">{{ $cat->name }}</td>
                    <td style="color:var(--text-muted)">{{ $cat->description ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $cat->medicines_count }}</span></td>
                    <td style="color:var(--text-muted);font-size:12px">{{ $cat->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-outline btn-sm btn-icon" onclick="editCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')">
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
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-tags"></i>
                            <h3>No Categories Found</h3>
                            <p>Add your first medicine category to get started</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px">{{ $categories->links('vendor.pagination.custom') }}</div>
</div>

<!-- Add Category Modal -->
<div class="modal-overlay" id="addCategoryModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus" style="color:#6366f1;margin-right:8px"></i>Add Category</h3>
            <button class="modal-close" onclick="closeModal('addCategoryModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Antibiotics" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Optional description..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addCategoryModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal-overlay" id="editCategoryModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="color:#6366f1;margin-right:8px"></i>Edit Category</h3>
            <button class="modal-close" onclick="closeModal('editCategoryModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" id="editCategoryForm">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" id="editName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="editDesc" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editCategoryModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editCategory(id, name, desc) {
    document.getElementById('editName').value = name;
    document.getElementById('editDesc').value = desc;
    document.getElementById('editCategoryForm').action = '/categories/' + id;
    openModal('editCategoryModal');
}
</script>
@endpush
