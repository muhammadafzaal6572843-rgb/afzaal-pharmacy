<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $sale->invoice_no }} — {{ $setting->pharmacy_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    /* =========================================================
       1. SCREEN VIEW STYLES (Modern Professional Pharmacy UI)
       ========================================================= */
    :root {
        --primary:       #6366f1;
        --primary-dark:  #4f46e5;
        --success:       #10b981;
        --warning:       #f59e0b;
        --danger:        #ef4444;
        --bg-dark:       #0f172a;
        --card-bg:       #ffffff;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--bg-dark);
        color: #1e293b;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 30px 16px 80px;
    }

    /* Screen Action Bar (Top) */
    .screen-bar {
        width: 100%; max-width: 820px;
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap; gap: 12px;
    }
    .screen-title { display: flex; align-items: center; gap: 10px; }
    .screen-title h1 { font-size: 18px; color: #f1f5f9; font-weight: 700; }
    .screen-title span { font-size: 12px; color: #94a3b8; background: rgba(255,255,255,.08); padding: 4px 10px; border-radius: 6px; }

    .actions { display: flex; align-items: center; gap: 12px; }
    .s-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 22px; border-radius: 12px;
        font-size: 14px; font-weight: 700;
        text-decoration: none; cursor: pointer; border: none;
        font-family: 'Inter', sans-serif;
        transition: all .2s ease;
        box-shadow: 0 4px 14px rgba(0,0,0,.3);
    }
    .s-btn-new {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        box-shadow: 0 4px 20px rgba(16,185,129,.4);
    }
    .s-btn-new:hover { opacity: .92; transform: translateY(-2px); box-shadow: 0 6px 24px rgba(16,185,129,.5); }
    
    .s-btn-print {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        box-shadow: 0 4px 20px rgba(99,102,241,.4);
    }
    .s-btn-print:hover { opacity: .92; transform: translateY(-2px); box-shadow: 0 6px 24px rgba(99,102,241,.5); }

    /* Invoice Card Container */
    .invoice-card {
        width: 100%; max-width: 820px;
        background: var(--card-bg);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 80px rgba(0,0,0,.5);
        border: 1px solid rgba(255,255,255,.1);
    }

    /* Header Banner */
    .inv-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 60%, #312e81 100%);
        padding: 36px 40px;
        display: flex; justify-content: space-between; align-items: flex-start;
        position: relative; overflow: hidden; color: #fff;
    }
    .inv-header::after {
        content: ''; position: absolute; bottom: -50px; right: -50px;
        width: 220px; height: 220px; border-radius: 50%;
        background: rgba(99,102,241,.15); pointer-events: none;
    }

    .pharmacy-brand { position: relative; z-index: 1; }
    .brand-icon-box {
        width: 48px; height: 48px; border-radius: 14px;
        background: linear-gradient(135deg, #6366f1, #10b981);
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; margin-bottom: 12px;
        box-shadow: 0 8px 24px rgba(99,102,241,.4);
    }
    .pharmacy-brand h2 { font-size: 24px; font-weight: 800; letter-spacing: -.5px; }
    .pharmacy-brand p { font-size: 13px; color: #94a3b8; margin-top: 4px; line-height: 1.6; }
    .pharmacy-lic { font-size: 11px; color: #818cf8; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; margin-top: 6px; }

    .inv-meta { text-align: right; position: relative; z-index: 1; }
    .inv-meta-label { font-size: 10.5px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
    .inv-meta-no { font-size: 26px; font-weight: 800; color: #a5b4fc; font-family: 'JetBrains Mono', monospace; margin: 2px 0 6px; }
    .inv-meta-date { font-size: 12px; color: #94a3b8; }
    .inv-status-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 14px; border-radius: 999px; font-size: 11.5px; font-weight: 700; margin-top: 10px;
    }
    .status-completed { background: rgba(16,185,129,.2); color: #34d399; border: 1px solid rgba(16,185,129,.4); }
    .status-pending   { background: rgba(245,158,11,.2);  color: #fbbf24; border: 1px solid rgba(245,158,11,.4); }

    /* Invoice Content Body */
    .inv-body { padding: 36px 40px; }

    .inv-info-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 24px;
        padding-bottom: 28px; margin-bottom: 28px;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-card-box {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 20px;
    }
    .info-card-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #64748b; margin-bottom: 6px; }
    .info-card-title { font-size: 16px; font-weight: 700; color: #0f172a; }
    .info-card-sub   { font-size: 12.5px; color: #64748b; margin-top: 3px; line-height: 1.5; }

    /* Real-time Pharmacy Itemized Table */
    .table-container { overflow-x: auto; margin-bottom: 28px; }
    .pharmacy-table { width: 100%; border-collapse: collapse; text-align: left; }
    .pharmacy-table thead tr { background: #f1f5f9; border-radius: 10px; }
    .pharmacy-table th {
        padding: 12px 14px; font-size: 11px; font-weight: 700;
        color: #475569; text-transform: uppercase; letter-spacing: .6px;
    }
    .pharmacy-table td {
        padding: 14px 14px; font-size: 13.5px; color: #1e293b;
        border-bottom: 1px solid #f1f5f9; vertical-align: middle;
    }
    .pharmacy-table tbody tr:hover { background: #faf5ff; }
    
    .med-name { font-weight: 700; color: #0f172a; font-size: 14px; }
    .med-sub  { font-size: 11px; color: #64748b; margin-top: 2px; }
    .badge-batch { background: #e0e7ff; color: #4338ca; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 6px; }
    .badge-exp { background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 6px; }

    /* Summary & Totals Row */
    .summary-section { display: flex; justify-content: flex-end; margin-top: 10px; }
    .summary-card {
        width: 100%; max-width: 340px;
        background: #f8fafc; border: 1px solid #e2e8f0;
        border-radius: 16px; padding: 20px;
    }
    .sum-row { display: flex; justify-content: space-between; font-size: 13.5px; padding: 6px 0; color: #475569; }
    .sum-row.grand-total {
        font-size: 20px; font-weight: 800; color: #059669;
        border-top: 2px solid #cbd5e1; padding-top: 12px; margin-top: 6px;
    }
    .sum-row.paid { color: #10b981; font-weight: 700; }
    .sum-row.change { background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 8px 12px; color: #047857; font-weight: 700; margin-top: 6px; }
    .sum-row.due { background: #fffbebf; border: 1px solid #fde68a; border-radius: 8px; padding: 8px 12px; color: #b45309; font-weight: 700; margin-top: 6px; }

    /* Pharmacy Disclaimer & Footer */
    .inv-footer {
        background: #f8fafc; border-top: 1px solid #e2e8f0;
        padding: 24px 40px; display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 16px;
    }
    .disclaimer-text { font-size: 11.5px; color: #64748b; max-width: 520px; line-height: 1.6; }
    .qr-badge {
        display: flex; flex-direction: column; align-items: center; gap: 4px;
        background: #fff; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 12px;
        font-size: 10px; font-weight: 700; color: #475569; text-transform: uppercase;
    }

    /* =========================================================
       2. REAL-TIME THERMAL RECEIPT PRINT STYLES (80mm / 58mm)
       ========================================================= */
    @media print {
        body {
            background: #fff !important;
            padding: 0 !important; margin: 0 !important;
            color: #000 !important;
        }
        .screen-bar, .invoice-card { display: none !important; }
        .thermal-print-wrapper {
            display: block !important;
            width: 80mm;
            margin: 0 auto;
            padding: 4mm 3mm;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 10pt;
            color: #000;
            line-height: 1.35;
        }

        .t-center { text-align: center; }
        .t-bold   { font-weight: bold; }
        .t-title  { font-size: 13pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .t-sub    { font-size: 8.5pt; }
        .t-line   { border-bottom: 1px dashed #000; margin: 5px 0; }
        .t-dline  { border-bottom: 2px solid #000; margin: 6px 0; }

        .t-row { display: flex; justify-content: space-between; font-size: 9.5pt; margin: 2px 0; }
        .t-row-lg { display: flex; justify-content: space-between; font-size: 12pt; font-weight: bold; margin: 4px 0; }

        .t-item-title { font-weight: bold; font-size: 9.5pt; margin-top: 4px; }
        .t-item-sub   { display: flex; justify-content: space-between; font-size: 8.5pt; padding-left: 3mm; color: #111; }

        .t-box { border: 1.5px solid #000; padding: 4px 6px; margin: 5px 0; }
    }

    /* Hide thermal print wrapper on computer screen */
    .thermal-print-wrapper { display: none; }
    </style>
</head>
<body>

{{-- ── SCREEN TOP ACTION BAR ── --}}
<div class="screen-bar">
    <div class="screen-title">
        <h1>🧾 Invoice #{{ $sale->invoice_no }}</h1>
        <span>{{ $sale->created_at->format('M d, Y · h:i A') }}</span>
    </div>
    <div class="actions">
        <a href="{{ route('pos.index') }}" class="s-btn s-btn-new" id="btnNextSale">
            <i class="fas fa-plus-circle"></i> ⚡ Start Next Sale (POS)
        </a>
        <button onclick="window.print()" class="s-btn s-btn-print">
            <i class="fas fa-print"></i> 🖨️ Print Receipt
        </button>
    </div>
</div>

{{-- ── SCREEN INVOICE CARD ── --}}
<div class="invoice-card">
    {{-- Header Banner --}}
    <div class="inv-header">
        <div class="pharmacy-brand">
            @if($setting->logo)
            <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" style="max-height:54px;max-width:180px;object-fit:contain;margin-bottom:10px;border-radius:10px;background:#fff;padding:4px">
            @else
            <div class="brand-icon-box">💊</div>
            @endif
            <h2>{{ $setting->pharmacy_name }}</h2>
            <p>
                @if($setting->address)<i class="fas fa-map-marker-alt" style="margin-right:4px"></i> {{ $setting->address }}<br>@endif
                @if($setting->phone)<i class="fas fa-phone-alt" style="margin-right:4px"></i> {{ $setting->phone }}@endif
                @if($setting->email) &nbsp;·&nbsp; <i class="fas fa-envelope" style="margin-right:4px"></i> {{ $setting->email }}@endif
            </p>
            <div class="pharmacy-lic">Licensed Pharmacy · DRAP Registered</div>
        </div>
        <div class="inv-meta">
            <div class="inv-meta-label">Invoice Number</div>
            <div class="inv-meta-no">{{ $sale->invoice_no }}</div>
            <div class="inv-meta-date">{{ $sale->created_at->format('d M Y, h:i A') }}</div>
            <div>
                <span class="inv-status-pill status-{{ $sale->status }}">
                    <i class="fas fa-check-circle"></i> {{ strtoupper($sale->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Body Content --}}
    <div class="inv-body">
        {{-- Info Grid --}}
        <div class="inv-info-grid">
            <div class="info-card-box">
                <div class="info-card-label"><i class="fas fa-user-circle"></i> Customer Info</div>
                @if($sale->customer)
                    <div class="info-card-title">{{ $sale->customer->name }}</div>
                    <div class="info-card-sub">
                        @if($sale->customer->phone)📞 {{ $sale->customer->phone }}<br>@endif
                        @if($sale->customer->address)📍 {{ $sale->customer->address }}@endif
                    </div>
                @else
                    <div class="info-card-title">Walk-in Customer</div>
                    <div class="info-card-sub">Counter Retail Sale</div>
                @endif
            </div>

            <div class="info-card-box">
                <div class="info-card-label"><i class="fas fa-id-badge"></i> Cashier & Payment</div>
                <div class="info-card-title">Served by: {{ $sale->user?->name ?? 'Pharmacist' }}</div>
                <div class="info-card-sub">
                    Payment Method: <strong>{{ strtoupper($sale->payment_method) }}</strong><br>
                    Transaction Time: {{ $sale->created_at->format('h:i:s A') }}
                </div>
            </div>
        </div>

        {{-- Medicine Items Table --}}
        <div class="table-container">
            <table class="pharmacy-table">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Medicine & Description</th>
                        <th>Batch No</th>
                        <th>Expiry</th>
                        <th style="text-align:center">Qty</th>
                        <th style="text-align:right">Price</th>
                        <th style="text-align:right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $idx => $item)
                    <tr>
                        <td style="color:#94a3b8;font-weight:600">{{ $idx + 1 }}</td>
                        <td>
                            <div class="med-name">{{ $item->medicine?->name ?? 'Medicine Item' }}</div>
                            @if($item->medicine?->manufacturer_name)
                            <div class="med-sub">{{ $item->medicine->manufacturer_name }}</div>
                            @endif
                        </td>
                        <td>
                            @if($item->medicine?->batch_no)
                            <span class="badge-batch">{{ $item->medicine->batch_no }}</span>
                            @else <span style="color:#94a3b8">-</span> @endif
                        </td>
                        <td>
                            @if($item->medicine?->expiry_date)
                            <span class="badge-exp">{{ $item->medicine->expiry_date->format('m/Y') }}</span>
                            @else <span style="color:#94a3b8">-</span> @endif
                        </td>
                        <td style="text-align:center;font-weight:700">{{ $item->quantity }} <span style="font-size:11px;color:#64748b;font-weight:400">{{ $item->medicine?->unit }}</span></td>
                        <td style="text-align:right;color:#475569;font-family:'JetBrains Mono',monospace">₨{{ number_format($item->sale_price, 2) }}</td>
                        <td style="text-align:right;font-weight:800;color:#0f172a;font-family:'JetBrains Mono',monospace">₨{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Summary Totals --}}
        <div class="summary-section">
            <div class="summary-card">
                <div class="sum-row">
                    <span>Subtotal</span>
                    <span style="font-family:'JetBrains Mono',monospace">₨{{ number_format($sale->subtotal, 2) }}</span>
                </div>
                @if($sale->discount > 0)
                <div class="sum-row" style="color:#ef4444">
                    <span>Discount</span>
                    <span style="font-family:'JetBrains Mono',monospace">-₨{{ number_format($sale->discount, 2) }}</span>
                </div>
                @endif
                @if($sale->tax > 0)
                <div class="sum-row">
                    <span>Tax</span>
                    <span style="font-family:'JetBrains Mono',monospace">₨{{ number_format($sale->tax, 2) }}</span>
                </div>
                @endif
                <div class="sum-row grand-total">
                    <span>Grand Total</span>
                    <span style="font-family:'JetBrains Mono',monospace">₨{{ number_format($sale->grand_total, 2) }}</span>
                </div>
                <div class="sum-row paid">
                    <span>Amount Paid</span>
                    <span style="font-family:'JetBrains Mono',monospace">₨{{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                @if($sale->paid_amount > $sale->grand_total)
                <div class="sum-row change">
                    <span>💵 Change Returned</span>
                    <span style="font-family:'JetBrains Mono',monospace">₨{{ number_format($sale->paid_amount - $sale->grand_total, 2) }}</span>
                </div>
                @endif
                @if($sale->due_amount > 0)
                <div class="sum-row due">
                    <span>⚠️ Balance Due</span>
                    <span style="font-family:'JetBrains Mono',monospace">₨{{ number_format($sale->due_amount, 2) }}</span>
                </div>
                @endif
            </div>
        </div>

        @if($sale->notes)
        <div style="margin-top:20px;padding:12px 16px;background:#fef9c3;border:1px solid #fef08a;border-radius:10px;font-size:13px;color:#854d0e">
            <strong>📝 Note:</strong> {{ $sale->notes }}
        </div>
        @endif
    </div>

    {{-- Footer Disclaimer --}}
    <div class="inv-footer">
        <div class="disclaimer-text">
            <strong>Pharmacy Disclaimer:</strong> Please check medicine name, dosage, and expiry before leaving the counter. Medicines requiring refrigeration & opened blister strips are non-refundable as per Drug Rules.
        </div>
        <div class="qr-badge">
            <i class="fas fa-qrcode" style="font-size:24px;color:#4f46e5;margin-bottom:2px"></i>
            <span>Verified Bill</span>
        </div>
    </div>
</div>

{{-- =========================================================
   3. THERMAL RECEIPT PRINT LAYOUT (80mm POS Printers)
   ========================================================= --}}
<div class="thermal-print-wrapper">
    <div class="t-center">
        @if($setting->logo)
        <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" style="max-height:42px;max-width:140px;object-fit:contain;margin-bottom:4px;display:block;margin-left:auto;margin-right:auto">
        @endif
        <div class="t-title">{{ $setting->pharmacy_name }}</div>
        @if($setting->address)<div class="t-sub">{{ $setting->address }}</div>@endif
        @if($setting->phone)<div class="t-sub">Ph: {{ $setting->phone }}</div>@endif
        <div class="t-sub">DRAP Reg / Licensed Pharmacy</div>
    </div>

    <div class="t-dline"></div>

    <div class="t-row"><span class="t-bold">Inv #</span><span>{{ $sale->invoice_no }}</span></div>
    <div class="t-row"><span class="t-bold">Date</span><span>{{ $sale->created_at->format('d/m/Y h:i A') }}</span></div>
    <div class="t-row"><span class="t-bold">Cashier</span><span>{{ $sale->user?->name ?? 'Staff' }}</span></div>
    <div class="t-row"><span class="t-bold">Customer</span><span>{{ $sale->customer?->name ?? 'Walk-in' }}</span></div>
    <div class="t-row"><span class="t-bold">Payment</span><span>{{ strtoupper($sale->payment_method) }}</span></div>

    <div class="t-line"></div>
    <div class="t-center t-bold" style="font-size:9pt">PURCHASED MEDICINES</div>
    <div class="t-line"></div>

    @foreach($sale->items as $idx => $item)
    <div class="t-item-title">{{ $idx + 1 }}. {{ $item->medicine?->name }}</div>
    <div class="t-item-sub">
        <span>
            {{ $item->quantity }} {{ $item->medicine?->unit }} × {{ number_format($item->sale_price, 2) }}
            @if($item->medicine?->batch_no) (B:{{ $item->medicine->batch_no }}) @endif
        </span>
        <span class="t-bold">₨{{ number_format($item->subtotal, 2) }}</span>
    </div>
    @endforeach

    <div class="t-line"></div>

    <div class="t-row"><span>Subtotal</span><span>₨{{ number_format($sale->subtotal, 2) }}</span></div>
    @if($sale->discount > 0)
    <div class="t-row"><span>Discount</span><span>-₨{{ number_format($sale->discount, 2) }}</span></div>
    @endif
    @if($sale->tax > 0)
    <div class="t-row"><span>Tax</span><span>₨{{ number_format($sale->tax, 2) }}</span></div>
    @endif

    <div class="t-dline"></div>

    <div class="t-row-lg">
        <span>TOTAL</span>
        <span>₨{{ number_format($sale->grand_total, 2) }}</span>
    </div>
    <div class="t-row t-bold"><span>PAID</span><span>₨{{ number_format($sale->paid_amount, 2) }}</span></div>

    @if($sale->paid_amount > $sale->grand_total)
    <div class="t-box t-center">
        <span class="t-bold">*** CHANGE RETURN: ₨{{ number_format($sale->paid_amount - $sale->grand_total, 2) }} ***</span>
    </div>
    @endif

    @if($sale->due_amount > 0)
    <div class="t-box t-center">
        <span class="t-bold">*** BALANCE DUE: ₨{{ number_format($sale->due_amount, 2) }} ***</span>
    </div>
    @endif

    <div class="t-line"></div>
    <div class="t-center t-sub" style="margin-top:6px">
        <div class="t-bold">THANK YOU FOR YOUR VISIT!</div>
        <div>Get well soon!</div>
        <div style="font-size:7.5pt;margin-top:4px">No refund without invoice / Cold items non-returnable</div>
    </div>
</div>

<script>
// Auto focus 'Next Sale' button or allow quick keyboard action
document.addEventListener('keydown', function(e) {
    // Enter key or F2 triggers Next Sale
    if (e.key === 'Enter' || e.key === 'F2') {
        window.location.href = "{{ route('pos.index') }}";
    }
    // Ctrl+P triggers print
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        window.print();
    }
});
</script>
</body>
</html>
