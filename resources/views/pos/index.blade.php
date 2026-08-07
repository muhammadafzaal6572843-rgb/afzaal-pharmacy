@extends('layouts.app')
@section('title', 'Point of Sale')

@push('styles')
<style>
.pos-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 20px;
    height: calc(100vh - 130px);
}
/* Left: Product Search & Grid */
.pos-left {
    display: flex; flex-direction: column; gap: 16px; overflow: hidden;
}
.pos-search-bar {
    position: relative;
}
.pos-search-bar input {
    width: 100%;
    background: var(--bg-card);
    border: 2px solid var(--border);
    border-radius: 14px;
    padding: 14px 18px 14px 50px;
    color: var(--text);
    font-size: 16px;
    font-family: 'Inter', sans-serif;
    transition: border-color .2s;
}
.pos-search-bar input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(99,102,241,.15);
}
.pos-search-bar i {
    position: absolute; left: 18px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted); font-size: 18px;
}
.search-results {
    position: absolute; top: calc(100% + 6px); left: 0; right: 0;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 14px;
    box-shadow: 0 20px 60px rgba(0,0,0,.5);
    max-height: 350px; overflow-y: auto;
    z-index: 100;
    display: none;
}
.search-results.show { display: block; }
.search-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px;
    cursor: pointer;
    transition: background .15s;
    border-bottom: 1px solid rgba(51,65,85,.4);
}
.search-item:last-child { border-bottom: none; }
.search-item:hover { background: rgba(99,102,241,.1); }
.search-item-name { font-weight: 600; font-size: 14px; }
.search-item-sub  { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }
.search-item-price { font-weight: 800; color: #34d399; font-size: 15px; }
.search-item-qty   { font-size: 12px; color: var(--text-muted); text-align: right; }
/* Cart */
.pos-cart {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    display: flex; flex-direction: column;
    overflow: hidden;
}
.cart-header {
    padding: 18px 20px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.cart-header h3 { font-size: 16px; font-weight: 700; }
.cart-items { flex: 1; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 8px; }
.cart-item {
    background: var(--bg-card2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 12px;
    display: flex; flex-direction: column; gap: 8px;
}
.cart-item-top { display: flex; align-items: center; justify-content: space-between; }
.cart-item-name { font-weight: 600; font-size: 13.5px; flex: 1; }
.cart-item-remove {
    width: 26px; height: 26px;
    border-radius: 6px; background: rgba(239,68,68,.15);
    border: none; color: #f87171; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
}
.cart-item-bottom { display: flex; align-items: center; gap: 8px; }
.qty-control { display: flex; align-items: center; gap: 0; }
.qty-btn {
    width: 28px; height: 28px; border-radius: 6px;
    background: var(--bg-card); border: 1px solid var(--border);
    color: var(--text); cursor: pointer; font-size: 14px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
}
.qty-btn:hover { background: var(--primary); border-color: var(--primary); }
.qty-display {
    width: 40px; text-align: center;
    background: none; border: 1px solid var(--border);
    color: var(--text); font-size: 13px; font-weight: 700;
    height: 28px; border-radius: 0;
    font-family: 'Inter', sans-serif;
}
.cart-item-price { margin-left: auto; font-weight: 800; color: #34d399; }
/* Cart Summary */
.cart-summary {
    padding: 16px 20px;
    border-top: 1px solid var(--border);
    display: flex; flex-direction: column; gap: 8px;
}
.summary-row { display: flex; justify-content: space-between; font-size: 13px; }
.summary-row.total { font-size: 20px; font-weight: 800; padding-top: 8px; border-top: 1px solid var(--border); }
.summary-row.total span:last-child { color: #34d399; }
.cart-actions { padding: 16px 20px; border-top: 1px solid var(--border); }
.btn-checkout {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #fff; border: none; border-radius: 14px;
    font-size: 16px; font-weight: 800;
    cursor: pointer; font-family: 'Inter', sans-serif;
    transition: all .2s;
    box-shadow: 0 6px 20px rgba(99,102,241,.4);
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-checkout:hover { opacity: .9; transform: translateY(-2px); }
.cart-empty { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text-muted); padding: 40px; text-align: center; }
.cart-empty i { font-size: 48px; opacity: .2; margin-bottom: 16px; }
.cart-empty p { font-size: 14px; }
/* Payment Modal */
.payment-grid { display: flex; gap: 10px; margin-bottom: 16px; }
.payment-option {
    flex: 1; padding: 14px; border-radius: 12px;
    border: 2px solid var(--border);
    text-align: center; cursor: pointer;
    transition: all .2s;
    background: var(--bg-card2);
}
.payment-option:hover, .payment-option.active {
    border-color: var(--primary);
    background: rgba(99,102,241,.1);
    color: var(--primary-light);
}
.payment-option i { font-size: 20px; display: block; margin-bottom: 6px; }
.payment-option span { font-size: 12px; font-weight: 600; }
/* Customer search */
.customer-dropdown {
    position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,.4);
    max-height: 200px; overflow-y: auto;
    z-index: 200; display: none;
}
.customer-dropdown.show { display: block; }
.customer-opt { padding: 10px 14px; cursor: pointer; font-size: 13px; transition: background .15s; border-bottom: 1px solid rgba(51,65,85,.3); }
.customer-opt:hover { background: rgba(99,102,241,.1); }
</style>
@endpush

@section('content')
<div class="pos-layout">
    <!-- LEFT: Search + Recent Products -->
    <div class="pos-left">
        <!-- Search -->
        <div class="pos-search-bar" style="position:relative">
            <i class="fas fa-barcode"></i>
            <input type="text" id="medicineSearch" placeholder="Search medicine by name or scan barcode..." autocomplete="off" autofocus>
            <div class="search-results" id="searchResults"></div>
        </div>

        <!-- Info box -->
        <div class="card" style="flex:1;overflow-y:auto">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(99,102,241,.15);display:flex;align-items:center;justify-content:center;font-size:18px;color:#818cf8">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <div style="font-weight:700">POS Terminal</div>
                    <div style="font-size:12px;color:var(--text-muted)">Search and add medicines to cart</div>
                </div>
            </div>
            <div id="recentItems" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(160px,1fr));gap:12px">
                <!-- Populated by JS after search -->
                <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-muted)">
                    <i class="fas fa-search" style="font-size:36px;opacity:.2;margin-bottom:12px;display:block"></i>
                    <p>Start searching for medicines above<br>or scan a barcode</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: Cart -->
    <div class="pos-cart">
        <div class="cart-header">
            <h3><i class="fas fa-shopping-cart" style="color:#6366f1;margin-right:8px"></i> Cart</h3>
            <button class="btn btn-outline btn-sm" onclick="clearCart()">
                <i class="fas fa-trash"></i> Clear
            </button>
        </div>

        <div class="cart-items" id="cartItems">
            <div class="cart-empty" id="cartEmpty">
                <i class="fas fa-shopping-cart"></i>
                <p>Cart is empty<br>Search and add medicines</p>
            </div>
        </div>

        <div class="cart-summary">
            <div class="summary-row">
                <span style="color:var(--text-muted)">Subtotal</span>
                <span id="cartSubtotal">₨ 0.00</span>
            </div>
            <div class="summary-row">
                <span style="color:var(--text-muted)">Discount ({{ $setting->default_discount ?? 0 }}%)</span>
                <span id="cartDiscount" style="color:#f87171">-₨ 0.00</span>
            </div>
            <div class="summary-row">
                <span style="color:var(--text-muted)">Tax ({{ $setting->tax }}%)</span>
                <span id="cartTax">₨ 0.00</span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span id="cartTotal">₨ 0.00</span>
            </div>
        </div>

        <div class="cart-actions">
            <button class="btn-checkout" onclick="openCheckout()">
                <i class="fas fa-credit-card"></i> Proceed to Payment
            </button>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal-overlay" id="checkoutModal">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fas fa-credit-card" style="color:#6366f1;margin-right:8px"></i>Payment Checkout</h3>
            <button class="modal-close" onclick="closeModal('checkoutModal')"><i class="fas fa-times"></i></button>
        </div>

        <form method="POST" action="{{ route('pos.store') }}" id="checkoutForm">
            @csrf
            <div class="modal-body">
                <!-- Summary -->
                <div style="background:var(--bg-card2);border-radius:12px;padding:16px;margin-bottom:20px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px">
                        <span style="color:var(--text-muted)">Subtotal</span>
                        <span id="mo_subtotal">₨ 0.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px">
                        <span style="color:var(--text-muted)">Discount</span>
                        <span id="mo_discount" style="color:#f87171">-₨ 0.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px">
                        <span style="color:var(--text-muted)">Tax</span>
                        <span id="mo_tax">₨ 0.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:20px;font-weight:800;border-top:1px solid var(--border);padding-top:10px">
                        <span>Grand Total</span>
                        <span style="color:#34d399" id="mo_total">₨ 0.00</span>
                    </div>
                </div>

                <!-- Customer -->
                <div class="form-group" style="position:relative">
                    <label class="form-label">Customer (Optional)</label>
                    <input type="text" id="customerSearch" class="form-control" placeholder="Search customer name or phone...">
                    <div class="customer-dropdown" id="customerDropdown">
                        @foreach($customers as $cust)
                        <div class="customer-opt" onclick="selectCustomer({{ $cust->id }}, '{{ addslashes($cust->name) }}')">
                            <strong>{{ $cust->name }}</strong>
                            <span style="color:var(--text-muted);font-size:11px;margin-left:8px">{{ $cust->phone }}</span>
                            @if($cust->credit_balance > 0)
                            <span style="color:#f59e0b;font-size:11px;margin-left:4px">(Balance: ₨{{ number_format($cust->credit_balance,0) }})</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="customer_id" id="selectedCustomerId">
                    <div id="selectedCustomerDisplay" style="margin-top:8px;display:none">
                        <span class="badge badge-success" id="selectedCustomerName"></span>
                        <button type="button" onclick="clearCustomer()" style="background:none;border:none;color:#f87171;cursor:pointer;margin-left:6px;font-size:12px">✕ Remove</button>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="form-group">
                    <label class="form-label">Payment Method *</label>
                    <div class="payment-grid">
                        <div class="payment-option active" onclick="selectPayment('cash')" id="pay_cash">
                            <i class="fas fa-money-bill-wave"></i><span>Cash</span>
                        </div>
                        <div class="payment-option" onclick="selectPayment('card')" id="pay_card">
                            <i class="fas fa-credit-card"></i><span>Card</span>
                        </div>
                        <div class="payment-option" onclick="selectPayment('credit')" id="pay_credit">
                            <i class="fas fa-handshake"></i><span>Credit</span>
                        </div>
                    </div>
                    <input type="hidden" name="payment_method" id="paymentMethod" value="cash">
                </div>

                <!-- Paid Amount -->
                <div class="form-group">
                    <label class="form-label">Amount Paid (₨) *</label>
                    <input type="number" name="paid_amount" id="paidAmount" class="form-control" placeholder="0.00" step="0.01" oninput="calcChange()" required>
                </div>

                <div style="display:flex;justify-content:space-between;background:var(--bg-card2);border-radius:10px;padding:12px 16px">
                    <div>
                        <div style="font-size:11px;color:var(--text-muted)">Change</div>
                        <div style="font-size:18px;font-weight:800;color:#34d399" id="changeAmount">₨ 0.00</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-muted)">Balance Due</div>
                        <div style="font-size:18px;font-weight:800;color:#f59e0b" id="dueAmount">₨ 0.00</div>
                    </div>
                </div>

                <div class="form-group" style="margin-top:16px">
                    <label class="form-label">Notes</label>
                    <input type="text" name="notes" class="form-control" placeholder="Optional notes...">
                </div>

                <!-- Hidden fields -->
                <input type="hidden" name="subtotal" id="h_subtotal">
                <input type="hidden" name="discount" id="h_discount">
                <input type="hidden" name="tax" id="h_tax">
                <input type="hidden" name="grand_total" id="h_grand_total">
                <div id="cartItemsInput"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('checkoutModal')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-checkout" style="width:auto;padding:12px 24px">
                    <i class="fas fa-check-circle"></i> Complete Sale
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const taxRate = {{ $setting->tax ?? 0 }};
const discountRate = {{ $setting->default_discount ?? 0 }};
let cart = [];
let searchTimeout;
let currentGrandTotal = 0;

// Medicine Search
document.getElementById('medicineSearch').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    if (q.length < 1) {
        hideSearchResults();
        return;
    }
    searchTimeout = setTimeout(() => searchMedicines(q), 200);
});

document.getElementById('medicineSearch').addEventListener('keydown', function(e) {
    if (e.key === 'Escape') hideSearchResults();
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('.pos-search-bar')) hideSearchResults();
    if (!e.target.closest('[style*="relative"]') && !e.target.closest('.customer-dropdown')) {
        hideCustomerDropdown();
    }
});

function searchMedicines(q) {
    fetch(`/pos/search?q=${encodeURIComponent(q)}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => showSearchResults(data));
}

function showSearchResults(items) {
    const container = document.getElementById('searchResults');
    if (!items.length) {
        container.innerHTML = '<div style="padding:20px;text-align:center;color:var(--text-muted)"><i class="fas fa-search" style="margin-bottom:8px;display:block"></i>No medicines found</div>';
        container.classList.add('show');
        return;
    }
    container.innerHTML = items.map(m => `
        <div class="search-item" onclick="addToCart(${m.id}, '${escHtml(m.name)}', ${m.sale_price}, ${m.quantity}, '${m.unit}')">
            <div>
                <div class="search-item-name">${m.name}</div>
                <div class="search-item-sub">${m.manufacturer_name || ''} ${m.category ? '· ' + m.category.name : ''}</div>
            </div>
            <div style="text-align:right">
                <div class="search-item-price">₨${parseFloat(m.sale_price).toFixed(2)}</div>
                <div class="search-item-qty">Stock: ${m.quantity} ${m.unit}</div>
            </div>
        </div>
    `).join('');
    container.classList.add('show');
}

function hideSearchResults() {
    document.getElementById('searchResults').classList.remove('show');
}

function escHtml(str) {
    return str.replace(/'/g, "\\'").replace(/"/g, '\\"');
}

// Cart Management
function addToCart(id, name, price, stock, unit) {
    hideSearchResults();
    document.getElementById('medicineSearch').value = '';

    const existing = cart.find(i => i.id === id);
    if (existing) {
        if (existing.qty >= stock) {
            alert(`Only ${stock} ${unit} in stock!`);
            return;
        }
        existing.qty++;
    } else {
        cart.push({ id, name, price: parseFloat(price), qty: 1, stock, unit });
    }
    renderCart();
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
}

function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    const newQty = item.qty + delta;
    if (newQty < 1) { removeFromCart(id); return; }
    if (newQty > item.stock) { alert(`Only ${item.stock} ${item.unit} in stock!`); return; }
    item.qty = newQty;
    renderCart();
}

function setQty(id, val) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    const q = parseInt(val);
    if (isNaN(q) || q < 1) { item.qty = 1; }
    else if (q > item.stock) { item.qty = item.stock; alert(`Only ${item.stock} ${item.unit} in stock!`); }
    else item.qty = q;
    renderCart();
}

function clearCart() {
    if (!cart.length) return;
    if (confirm('Clear the entire cart?')) { cart = []; renderCart(); }
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const emptyEl   = document.getElementById('cartEmpty');

    if (!cart.length) {
        container.innerHTML = '';
        container.appendChild(createEmptyEl());
        updateSummary();
        return;
    }

    container.innerHTML = cart.map(item => `
        <div class="cart-item">
            <div class="cart-item-top">
                <div class="cart-item-name">${item.name}</div>
                <button class="cart-item-remove" onclick="removeFromCart(${item.id})"><i class="fas fa-times"></i></button>
            </div>
            <div class="cart-item-bottom">
                <div class="qty-control">
                    <button type="button" class="qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
                    <input type="number" class="qty-display" value="${item.qty}" min="1" max="${item.stock}" onchange="setQty(${item.id}, this.value)">
                    <button type="button" class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
                </div>
                <span style="font-size:12px;color:var(--text-muted);margin-left:8px">× ₨${item.price.toFixed(2)}</span>
                <span class="cart-item-price" style="margin-left:auto">₨${(item.qty * item.price).toFixed(2)}</span>
            </div>
        </div>
    `).join('');

    updateSummary();
}

function createEmptyEl() {
    const div = document.createElement('div');
    div.className = 'cart-empty';
    div.innerHTML = '<i class="fas fa-shopping-cart"></i><p>Cart is empty<br>Search and add medicines</p>';
    return div;
}

function updateSummary() {
    const subtotal = cart.reduce((s, i) => s + i.qty * i.price, 0);
    const discount = subtotal * (discountRate / 100);
    const taxAmount = (subtotal - discount) * (taxRate / 100);
    const grand = Math.max(0, subtotal - discount + taxAmount);
    currentGrandTotal = grand;

    document.getElementById('cartSubtotal').textContent = '₨ ' + subtotal.toFixed(2);
    const cdEl = document.getElementById('cartDiscount');
    if (cdEl) cdEl.textContent = '-₨ ' + discount.toFixed(2);
    document.getElementById('cartTax').textContent = '₨ ' + taxAmount.toFixed(2);
    document.getElementById('cartTotal').textContent = '₨ ' + grand.toFixed(2);
}

// Checkout
function openCheckout() {
    if (!cart.length) { alert('Cart is empty!'); return; }

    const subtotal = cart.reduce((s, i) => s + i.qty * i.price, 0);
    const discount = subtotal * (discountRate / 100);
    const taxAmount = (subtotal - discount) * (taxRate / 100);
    const grand = Math.max(0, subtotal - discount + taxAmount);

    document.getElementById('mo_subtotal').textContent = '₨ ' + subtotal.toFixed(2);
    document.getElementById('mo_discount').textContent = '-₨ ' + discount.toFixed(2);
    document.getElementById('mo_tax').textContent = '₨ ' + taxAmount.toFixed(2);
    document.getElementById('mo_total').textContent = '₨ ' + grand.toFixed(2);
    document.getElementById('paidAmount').value = grand.toFixed(2);
    document.getElementById('h_subtotal').value = subtotal.toFixed(2);
    document.getElementById('h_discount').value = discount.toFixed(2);
    document.getElementById('h_tax').value = taxAmount.toFixed(2);
    document.getElementById('h_grand_total').value = grand.toFixed(2);
    currentGrandTotal = grand;

    // Build hidden cart inputs
    const cartInput = document.getElementById('cartItemsInput');
    cartInput.innerHTML = cart.map((item, i) => `
        <input type="hidden" name="items[${i}][medicine_id]" value="${item.id}">
        <input type="hidden" name="items[${i}][quantity]" value="${item.qty}">
        <input type="hidden" name="items[${i}][sale_price]" value="${item.price}">
    `).join('');

    calcChange();
    openModal('checkoutModal');
}

function calcChange() {
    const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
    const change = paid - currentGrandTotal;
    const due    = currentGrandTotal - paid;
    document.getElementById('changeAmount').textContent = '₨ ' + Math.max(0, change).toFixed(2);
    document.getElementById('dueAmount').textContent    = '₨ ' + Math.max(0, due).toFixed(2);
}

function selectPayment(method) {
    document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('active'));
    document.getElementById('pay_' + method).classList.add('active');
    document.getElementById('paymentMethod').value = method;

    if (method === 'credit') {
        document.getElementById('paidAmount').value = '0';
        calcChange();
    } else if (method === 'card' || method === 'cash') {
        document.getElementById('paidAmount').value = currentGrandTotal.toFixed(2);
        calcChange();
    }
}

// Customer search
document.getElementById('customerSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    const opts = document.querySelectorAll('.customer-opt');
    let visible = 0;
    opts.forEach(opt => {
        const match = opt.textContent.toLowerCase().includes(q);
        opt.style.display = match ? 'block' : 'none';
        if (match) visible++;
    });
    if (q) { document.getElementById('customerDropdown').classList.add('show'); }
    else   { document.getElementById('customerDropdown').classList.remove('show'); }
});

function selectCustomer(id, name) {
    document.getElementById('selectedCustomerId').value = id;
    document.getElementById('selectedCustomerName').textContent = '👤 ' + name;
    document.getElementById('selectedCustomerDisplay').style.display = 'block';
    document.getElementById('customerSearch').value = '';
    hideCustomerDropdown();
}

function clearCustomer() {
    document.getElementById('selectedCustomerId').value = '';
    document.getElementById('selectedCustomerDisplay').style.display = 'none';
}

function hideCustomerDropdown() {
    document.getElementById('customerDropdown').classList.remove('show');
}

// Global POS Keyboard Shortcuts
document.addEventListener('keydown', function(e) {
    // F2: Focus Search Input
    if (e.key === 'F2') {
        e.preventDefault();
        const searchInput = document.getElementById('medicineSearch');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
    // F9 or Ctrl+Enter: Open Checkout Modal
    if (e.key === 'F9' || (e.ctrlKey && e.key === 'Enter')) {
        e.preventDefault();
        openCheckout();
    }
    // Escape: Close modals or search dropdowns
    if (e.key === 'Escape') {
        hideSearchResults();
        hideCustomerDropdown();
        closeModal('checkoutModal');
    }
});
</script>
@endpush
