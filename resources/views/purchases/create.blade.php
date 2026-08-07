@extends('layouts.app')
@section('title', 'New Purchase')

@push('styles')
<style>
.item-row { display:grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap:10px; align-items:end; margin-bottom:12px; background:var(--bg-card2); padding:14px; border-radius:12px; border:1px solid var(--border); }
#items-container { display:flex; flex-direction:column; gap:0; }
.subtotal-val { font-size:18px; font-weight:800; color:#34d399; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h2>Record New Purchase</h2>
        <p>Enter purchase details and medicines received</p>
    </div>
    <a href="{{ route('purchases.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="{{ route('purchases.store') }}" id="purchaseForm">
    @csrf
    <div class="grid grid-2" style="align-items:start">
        <!-- Left: Purchase Info -->
        <div class="card">
            <div class="card-header"><h3 class="card-title">Purchase Details</h3></div>
            <div class="form-group">
                <label class="form-label">Supplier *</label>
                <select name="supplier_id" class="form-control" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}">{{ $sup->name }} {{ $sup->company ? '('.$sup->company.')' : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Purchase Date *</label>
                <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes..."></textarea>
            </div>
            <div style="background:var(--bg-card2);border:1px solid var(--border);border-radius:12px;padding:16px;margin-top:8px">
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:6px">Grand Total</div>
                <div class="subtotal-val" id="grandTotalDisplay">₨ 0.00</div>
            </div>
            <div style="margin-top:20px;display:flex;gap:10px;justify-content:flex-end">
                <a href="{{ route('purchases.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Purchase</button>
            </div>
        </div>

        <!-- Right: Medicine Items -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Medicine Items</h3>
                <button type="button" class="btn btn-primary btn-sm" onclick="addItem()">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>
            <div id="items-container"></div>
        </div>
    </div>
</form>

<!-- Medicine data for JS -->
<script>
const medicines = @json($medicines->map(fn($m) => ['id' => $m->id, 'name' => $m->name, 'purchase_price' => $m->purchase_price, 'unit' => $m->unit]));
let itemCount = 0;

function addItem() {
    const container = document.getElementById('items-container');
    const i = itemCount++;
    const div = document.createElement('div');
    div.className = 'item-row';
    div.id = 'item_' + i;

    const opts = medicines.map(m => `<option value="${m.id}" data-price="${m.purchase_price}" data-unit="${m.unit}">${m.name} (${m.unit})</option>`).join('');

    div.innerHTML = `
        <div class="form-group" style="margin:0">
            <label class="form-label">Medicine</label>
            <select name="items[${i}][medicine_id]" class="form-control" onchange="fillPrice(this, ${i})" required>
                <option value="">Select...</option>${opts}
            </select>
        </div>
        <div class="form-group" style="margin:0">
            <label class="form-label">Qty *</label>
            <input type="number" name="items[${i}][quantity]" class="form-control qty-input" min="1" placeholder="0" oninput="calcTotal()" required>
        </div>
        <div class="form-group" style="margin:0">
            <label class="form-label">Unit Price (₨)</label>
            <input type="number" name="items[${i}][purchase_price]" id="price_${i}" class="form-control price-input" step="0.01" placeholder="0.00" oninput="calcTotal()" required>
        </div>
        <div class="form-group" style="margin:0">
            <label class="form-label">Subtotal</label>
            <input type="text" id="sub_${i}" class="form-control" readonly placeholder="0.00" style="color:#34d399;font-weight:700">
        </div>
        <div style="padding-top:22px">
            <button type="button" class="btn btn-danger btn-sm btn-icon" onclick="removeItem(${i})"><i class="fas fa-trash"></i></button>
        </div>
    `;
    container.appendChild(div);
}

function fillPrice(sel, i) {
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('price_' + i).value = opt.dataset.price || '';
    calcTotal();
}

function removeItem(i) {
    document.getElementById('item_' + i)?.remove();
    calcTotal();
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('.qty-input').forEach((qty, idx) => {
        const priceInput = qty.closest('.item-row').querySelector('.price-input');
        const subInput   = qty.closest('.item-row').querySelector('[id^="sub_"]');
        const q = parseFloat(qty.value) || 0;
        const p = parseFloat(priceInput?.value) || 0;
        const sub = q * p;
        if (subInput) subInput.value = '₨ ' + sub.toFixed(2);
        total += sub;
    });
    document.getElementById('grandTotalDisplay').textContent = '₨ ' + total.toFixed(2);
}

// Add first item row by default
addItem();
</script>
@endsection
