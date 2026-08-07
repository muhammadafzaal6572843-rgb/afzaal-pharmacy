@extends('layouts.app')
@section('title', 'Edit Medicine')

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Medicine</h2>
        <p>Update medicine information</p>
    </div>
    <a href="{{ route('medicines.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="{{ route('medicines.update', $medicine) }}">
    @csrf @method('PUT')
    <div class="grid grid-2">
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Basic Information</h3></div>
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-control" required>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $medicine->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Medicine Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $medicine->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Manufacturer</label>
                    <input type="text" name="manufacturer_name" class="form-control" value="{{ old('manufacturer_name', $medicine->manufacturer_name) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Barcode</label>
                    <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $medicine->barcode) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Batch No.</label>
                    <input type="text" name="batch_no" class="form-control" value="{{ old('batch_no', $medicine->batch_no) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Unit</label>
                    <select name="unit" class="form-control">
                        @foreach(['pcs','strip','tablet','bottle','syrup','injection','sachet','tube'] as $u)
                        <option value="{{ $u }}" {{ old('unit', $medicine->unit) == $u ? 'selected' : '' }}>{{ ucfirst($u) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $medicine->description) }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Pricing & Stock</h3></div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Purchase Price (₨) *</label>
                        <input type="number" name="purchase_price" class="form-control" step="0.01" value="{{ old('purchase_price', $medicine->purchase_price) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sale Price (₨) *</label>
                        <input type="number" name="sale_price" class="form-control" step="0.01" value="{{ old('sale_price', $medicine->sale_price) }}" required>
                    </div>
                </div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Current Quantity *</label>
                        <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $medicine->quantity) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reorder Level *</label>
                        <input type="number" name="reorder_level" class="form-control" value="{{ old('reorder_level', $medicine->reorder_level) }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $medicine->expiry_date?->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ $medicine->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $medicine->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px">
                <a href="{{ route('medicines.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Medicine</button>
            </div>
        </div>
    </div>
</form>
@endsection
