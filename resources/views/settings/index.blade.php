@extends('layouts.app')
@section('title', 'System Settings')

@section('content')
<div class="page-header">
    <div>
        <h2>System Settings</h2>
        <p>Configure pharmacy details and system preferences</p>
    </div>
</div>

<form method="POST" action="{{ route('settings.update', $setting) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="grid grid-2" style="align-items:start">
        <!-- Pharmacy Info -->
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-clinic-medical" style="color:#6366f1;margin-right:8px"></i>Pharmacy Information</h3></div>
            <div class="form-group">
                <label class="form-label">Pharmacy Name *</label>
                <input type="text" name="pharmacy_name" class="form-control" value="{{ old('pharmacy_name', $setting->pharmacy_name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $setting->phone) }}" placeholder="+92-XXX-XXXXXXX">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $setting->email) }}" placeholder="info@pharmacy.com">
            </div>
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3" placeholder="Full pharmacy address...">{{ old('address', $setting->address) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Logo</label>
                @if($setting->logo)
                <div style="margin-bottom:10px">
                    <img src="{{ Storage::url($setting->logo) }}" alt="Logo" style="height:60px;border-radius:8px;border:1px solid var(--border)">
                </div>
                @endif
                <input type="file" name="logo" class="form-control" accept="image/*">
                <p style="font-size:11px;color:var(--text-muted);margin-top:6px">JPG/PNG, max 2MB</p>
            </div>
        </div>

        <!-- System Config -->
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-cog" style="color:#6366f1;margin-right:8px"></i>System Configuration</h3></div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Currency Name *</label>
                        <input type="text" name="currency" class="form-control" value="{{ old('currency', $setting->currency) }}" placeholder="PKR" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Currency Symbol *</label>
                        <input type="text" name="currency_symbol" class="form-control" value="{{ old('currency_symbol', $setting->currency_symbol) }}" placeholder="₨" required>
                    </div>
                </div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Tax Rate (%)</label>
                        <input type="number" name="tax" class="form-control" value="{{ old('tax', $setting->tax) }}" step="0.01" min="0" max="100" placeholder="0">
                        <p style="font-size:11px;color:var(--text-muted);margin-top:6px">Enter 0 to disable tax</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fixed Discount Rate (%) *</label>
                        <input type="number" name="default_discount" class="form-control" value="{{ old('default_discount', $setting->default_discount) }}" step="0.01" min="0" max="100" placeholder="0">
                        <p style="font-size:11px;color:var(--text-muted);margin-top:6px">Fixed discount applied to sales</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Invoice Prefix</label>
                    <input type="text" name="invoice_prefix" class="form-control" value="{{ old('invoice_prefix', $setting->invoice_prefix) }}" placeholder="INV" maxlength="10">
                    <p style="font-size:11px;color:var(--text-muted);margin-top:6px">e.g. "INV" → Invoice will be INV-20240101-0001</p>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
