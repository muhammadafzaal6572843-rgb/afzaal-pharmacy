@extends('layouts.app')
@section('title', 'Add Medicine')

@section('content')
<div class="page-header">
    <div>
        <h2>Add New Medicine</h2>
        <p>Add a new medicine to the inventory</p>
    </div>
    <a href="{{ route('medicines.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="{{ route('medicines.store') }}">
    @csrf
    <div class="grid grid-2">
        <!-- Left Column -->
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Basic Information</h3></div>
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Medicine Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Panadol 500mg" value="{{ old('name') }}" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Manufacturer</label>
                    <input type="text" name="manufacturer_name" class="form-control" placeholder="e.g. GSK Pakistan" value="{{ old('manufacturer_name') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Barcode</label>
                    <input type="text" name="barcode" class="form-control" placeholder="Scan or enter barcode" value="{{ old('barcode') }}">
                    @error('barcode')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Batch No.</label>
                    <input type="text" name="batch_no" class="form-control" placeholder="e.g. B-90412" value="{{ old('batch_no') }}">
                    @error('batch_no')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Unit</label>
                    <select name="unit" class="form-control">
                        <option value="pcs">Pieces (pcs)</option>
                        <option value="strip">Strip</option>
                        <option value="tablet">Tablet</option>
                        <option value="bottle">Bottle</option>
                        <option value="syrup">Syrup</option>
                        <option value="injection">Injection</option>
                        <option value="sachet">Sachet</option>
                        <option value="tube">Tube</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Optional notes...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Pricing & Stock</h3></div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Purchase Price (₨) *</label>
                        <input type="number" name="purchase_price" class="form-control" placeholder="0.00" step="0.01" value="{{ old('purchase_price') }}" required>
                        @error('purchase_price')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sale Price (₨) *</label>
                        <input type="number" name="sale_price" class="form-control" placeholder="0.00" step="0.01" value="{{ old('sale_price') }}" required>
                        @error('sale_price')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Current Quantity *</label>
                        <input type="number" name="quantity" class="form-control" placeholder="0" value="{{ old('quantity', 0) }}" required>
                        @error('quantity')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reorder Level *</label>
                        <input type="number" name="reorder_level" class="form-control" placeholder="10" value="{{ old('reorder_level', 10) }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px">
                <a href="{{ route('medicines.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Medicine</button>
            </div>
        </div>
    </div>
</form>
@endsection
