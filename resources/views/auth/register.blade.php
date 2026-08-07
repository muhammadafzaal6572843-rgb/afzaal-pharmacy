<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — {{ \App\Models\Setting::get()->pharmacy_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
        width: 100%; height: 100%;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: #0f172a;
        min-height: 100vh;
        min-height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 16px;
        position: relative;
        overflow-x: hidden;
    }

    /* ── Animated Orbs ── */
    .orbs { position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none; }
    .orb  { position: absolute; border-radius: 50%; filter: blur(75px); opacity: .2; animation: floatOrb 9s ease-in-out infinite; }
    .orb-1 { width: clamp(200px,40vw,420px); height: clamp(200px,40vw,420px); background: #10b981; top: -80px;  left: -80px;  animation-delay: 0s; }
    .orb-2 { width: clamp(150px,30vw,320px); height: clamp(150px,30vw,320px); background: #6366f1; bottom: -60px; right: -60px; animation-delay: 3s; }
    .orb-3 { width: clamp(100px,20vw,220px); height: clamp(100px,20vw,220px); background: #8b5cf6; top: 40%;    left: 55%;    animation-delay: 6s; }
    @keyframes floatOrb {
        0%,100% { transform: translate(0,0) scale(1); }
        50%      { transform: translate(18px,-18px) scale(1.06); }
    }

    /* ── Card wrapper ── */
    .register-wrapper {
        width: 100%;
        max-width: 480px;
        position: relative;
        z-index: 1;
        margin-left: auto;
        margin-right: auto;
    }

    /* ── Brand ── */
    .register-brand {
        text-align: center;
        margin-bottom: 24px;
    }
    .brand-icon {
        width: clamp(54px,12vw,68px);
        height: clamp(54px,12vw,68px);
        background: linear-gradient(135deg, #10b981, #6366f1);
        border-radius: 20px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: clamp(24px,5vw,30px);
        margin-bottom: 12px;
        box-shadow: 0 10px 40px rgba(16,185,129,.35);
    }
    .register-brand h1 {
        font-size: clamp(20px,5vw,26px);
        font-weight: 800; color: #f1f5f9; letter-spacing: -.4px;
    }
    .register-brand p { font-size: 13px; color: #94a3b8; margin-top: 4px; }

    /* ── Card ── */
    .register-card {
        background: rgba(30,41,59,.85);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(51,65,85,.8);
        border-radius: 22px;
        padding: clamp(24px,5vw,36px);
        box-shadow: 0 24px 80px rgba(0,0,0,.55);
    }
    .register-card h2 { font-size: 19px; font-weight: 700; color: #f1f5f9; margin-bottom: 5px; }
    .register-card .subtitle { font-size: 13px; color: #64748b; margin-bottom: 22px; }

    /* ── Form ── */
    .form-group { margin-bottom: 16px; }
    .form-label {
        display: block; font-size: 11.5px; font-weight: 600;
        color: #94a3b8; margin-bottom: 6px;
        text-transform: uppercase; letter-spacing: .6px;
    }
    .input-wrap { position: relative; }
    .input-wrap i {
        position: absolute; left: 14px; top: 50%;
        transform: translateY(-50%);
        color: #64748b; font-size: 14px;
        pointer-events: none;
    }
    .form-control {
        width: 100%;
        background: rgba(15,23,42,.65);
        border: 1px solid #334155;
        border-radius: 11px;
        padding: 11px 14px 11px 42px;
        color: #f1f5f9; font-size: 13.5px;
        font-family: 'Inter', sans-serif;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .form-control:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16,185,129,.2);
        background: rgba(15,23,42,.9);
    }
    .form-control::placeholder { color: #475569; }

    select.form-control {
        appearance: none; -webkit-appearance: none;
        cursor: pointer; color: #f1f5f9;
    }
    select.form-control option { background: #1e293b; color: #f1f5f9; }

    .eye-btn {
        position: absolute; right: 14px; top: 50%;
        transform: translateY(-50%);
        width: 32px; height: 32px;
        display: inline-flex; align-items: center; justify-content: center;
        background: none; border: none;
        color: #94a3b8; cursor: pointer; font-size: 15px; z-index: 10;
    }

    /* ── Error ── */
    .error-msg {
        background: rgba(239,68,68,.1);
        border: 1px solid rgba(239,68,68,.3);
        border-radius: 10px;
        padding: 10px 14px; font-size: 13px;
        color: #fca5a5; margin-bottom: 18px;
        display: flex; align-items: center; gap: 8px;
    }

    /* ── Button ── */
    .btn-register {
        width: 100%;
        padding: 13px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff; border: none; border-radius: 12px;
        font-size: 15px; font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all .22s;
        box-shadow: 0 8px 24px rgba(16,185,129,.35);
        display: flex; align-items: center; justify-content: center; gap: 8px;
        margin-top: 8px;
    }
    .btn-register:hover { opacity: .92; transform: translateY(-2px); box-shadow: 0 12px 30px rgba(16,185,129,.45); }
    .btn-register:active { transform: translateY(0); }

    .login-footer {
        margin-top: 20px; text-align: center; font-size: 13px; color: #64748b;
    }
    .login-footer a { color: #34d399; font-weight: 600; text-decoration: none; }
    .login-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="orbs">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<div class="register-wrapper">
    {{-- Brand --}}
    @php $regSetting = \App\Models\Setting::get(); @endphp
    <div class="register-brand">
        @if($regSetting->logo)
        <img src="{{ asset('storage/' . $regSetting->logo) }}" alt="Logo" style="max-height:64px;max-width:180px;object-fit:contain;margin-bottom:12px;border-radius:12px">
        @else
        <div class="brand-icon">💊</div>
        @endif
        <h1>{{ $regSetting->pharmacy_name }}</h1>
        <p>Pharmacy Management Registration</p>
    </div>

    {{-- Card --}}
    <div class="register-card">
        <h2>Create Account 🚀</h2>
        <p class="subtitle">Register a new staff or manager account</p>

        @if($errors->any())
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Full Name *</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Dr. Muhammad Ali" value="{{ old('name') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address *</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" class="form-control" placeholder="ali@pharmacy.com" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Phone Number</label>
                <div class="input-wrap">
                    <i class="fas fa-phone"></i>
                    <input type="text" id="phone" name="phone" class="form-control" placeholder="+92 300 1234567" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="role">Select Role *</label>
                <div class="input-wrap">
                    <i class="fas fa-user-shield"></i>
                    <select id="role" name="role" class="form-control" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $r)
                        <option value="{{ $r->name }}" {{ old('role') == $r->name ? 'selected' : '' }}>
                            {{ $r->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password *</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" style="padding-right:48px" required>
                    <button type="button" class="eye-btn" onclick="togglePass('password', 'eye1')">
                        <i class="fas fa-eye" id="eye1"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm Password *</label>
                <div class="input-wrap">
                    <i class="fas fa-check-double"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="••••••••" style="padding-right:48px" required>
                    <button type="button" class="eye-btn" onclick="togglePass('password_confirmation', 'eye2')">
                        <i class="fas fa-eye" id="eye2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="login-footer">
            Already have an account? <a href="{{ route('login') }}">Sign In here</a>
        </div>
    </div>
</div>

<script>
function togglePass(inputId, eyeId) {
    const inp = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);
    if (inp.type === 'password') {
        inp.type = 'text';
        eye.className = 'fas fa-eye-slash';
        eye.style.color = '#34d399';
    } else {
        inp.type = 'password';
        eye.className = 'fas fa-eye';
        eye.style.color = '#a5b4fc';
    }
}
</script>
</body>
</html>
