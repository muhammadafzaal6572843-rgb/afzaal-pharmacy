<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ \App\Models\Setting::get()->pharmacy_name }}</title>
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
        min-height: 100dvh;          /* dynamic viewport on mobile */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px 16px;          /* breathing room on mobile */
        position: relative;
        overflow-x: hidden;
    }

    /* ── Animated background ── */
    .orbs { position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none; }
    .orb  { position: absolute; border-radius: 50%; filter: blur(70px); opacity: .18; animation: floatOrb 9s ease-in-out infinite; }
    .orb-1 { width: clamp(200px,40vw,420px); height: clamp(200px,40vw,420px); background: #6366f1; top: -80px;  left: -80px;  animation-delay: 0s; }
    .orb-2 { width: clamp(150px,30vw,320px); height: clamp(150px,30vw,320px); background: #10b981; bottom: -60px; right: -60px; animation-delay: 3s; }
    .orb-3 { width: clamp(100px,20vw,220px); height: clamp(100px,20vw,220px); background: #8b5cf6; top: 40%;    left: 55%;    animation-delay: 6s; }
    @keyframes floatOrb {
        0%,100% { transform: translate(0,0)    scale(1);    }
        50%      { transform: translate(18px,-18px) scale(1.06); }
    }

    /* ── Card wrapper ── */
    .login-wrapper {
        width: 100%;
        max-width: 440px;
        position: relative;
        z-index: 1;

        /* Center even if flex fails */
        margin-left:  auto;
        margin-right: auto;
    }

    /* ── Brand ── */
    .login-brand {
        text-align: center;
        margin-bottom: 28px;
    }
    .brand-icon {
        width: clamp(60px,14vw,76px);
        height: clamp(60px,14vw,76px);
        background: linear-gradient(135deg, #6366f1, #10b981);
        border-radius: 20px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: clamp(26px,6vw,34px);
        margin-bottom: 14px;
        box-shadow: 0 10px 40px rgba(99,102,241,.4);
    }
    .login-brand h1 {
        font-size: clamp(20px,5vw,26px);
        font-weight: 800; color: #f1f5f9; letter-spacing: -.4px;
    }
    .login-brand p { font-size: 13px; color: #94a3b8; margin-top: 5px; }

    /* ── Card ── */
    .login-card {
        background: rgba(30,41,59,.85);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(51,65,85,.8);
        border-radius: 22px;
        padding: clamp(24px,5vw,36px);
        box-shadow: 0 24px 80px rgba(0,0,0,.55);
    }
    .login-card h2 { font-size: 19px; font-weight: 700; color: #f1f5f9; margin-bottom: 5px; }
    .login-card .subtitle { font-size: 13px; color: #64748b; margin-bottom: 26px; }

    /* ── Form ── */
    .form-group { margin-bottom: 18px; }
    .form-label {
        display: block; font-size: 11.5px; font-weight: 600;
        color: #94a3b8; margin-bottom: 7px;
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
        padding: 12px 14px 12px 42px;
        color: #f1f5f9; font-size: 14px;
        font-family: 'Inter', sans-serif;
        transition: border-color .2s, box-shadow .2s, background .2s;
        -webkit-appearance: none; /* iOS fix */
    }
    .form-control:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.2);
        background: rgba(15,23,42,.9);
    }
    .form-control::placeholder { color: #475569; }

    /* ── Error ── */
    .error-msg {
        background: rgba(239,68,68,.1);
        border: 1px solid rgba(239,68,68,.3);
        border-radius: 10px;
        padding: 10px 14px; font-size: 13px;
        color: #fca5a5; margin-bottom: 18px;
        display: flex; align-items: center; gap: 8px;
    }

    /* ── Remember row ── */
    .remember-row {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 22px;
    }
    .checkbox-label {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; color: #94a3b8; cursor: pointer;
        user-select: none;
    }
    .checkbox-label input { width: 16px; height: 16px; accent-color: #6366f1; cursor: pointer; }

    /* ── Button ── */
    .btn-login {
        width: 100%;
        padding: 13px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff; border: none; border-radius: 12px;
        font-size: 15px; font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all .22s;
        box-shadow: 0 8px 24px rgba(99,102,241,.4);
        display: flex; align-items: center; justify-content: center; gap: 8px;
        touch-action: manipulation; /* better tap on mobile */
    }
    .btn-login:hover  { opacity: .9; transform: translateY(-2px); box-shadow: 0 12px 34px rgba(99,102,241,.5); }
    .btn-login:active { transform: translateY(0); }

    /* ── Credentials hint ── */
    .credentials-hint {
        margin-top: 22px; padding-top: 18px;
        border-top: 1px solid rgba(30,41,59,.8);
        display: grid; gap: 7px;
    }
    .hint-title {
        font-size: 10.5px; color: #475569; font-weight: 600;
        text-transform: uppercase; letter-spacing: .5px;
        margin-bottom: 2px;
    }
    .cred-item {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 4px;
        font-size: 12px;
        background: rgba(99,102,241,.08);
        border: 1px solid rgba(99,102,241,.1);
        border-radius: 8px;
        padding: 7px 11px;
    }
    .cred-role  { color: #94a3b8; font-weight: 500; }
    .cred-email { color: #a5b4fc; font-weight: 600; font-family: monospace; font-size: 11.5px; }

    /* ── Responsive ── */
    /* Tablet & small laptop */
    @media (max-width: 600px) {
        .login-wrapper { max-width: 100%; }
        .login-card { padding: 22px 20px; border-radius: 18px; }
        .brand-icon  { border-radius: 16px; }
    }

    /* Mobile */
    @media (max-width: 420px) {
        body { padding: 16px 12px; align-items: flex-start; padding-top: 40px; }
        .login-brand { margin-bottom: 20px; }
        .login-brand p { font-size: 12px; }
        .login-card { padding: 20px 16px; border-radius: 16px; }
        .form-control { font-size: 16px; } /* prevent iOS zoom */
        .cred-email  { font-size: 11px; }
        .btn-login   { padding: 14px; font-size: 15px; } /* bigger tap area */
    }

    /* Very small screens */
    @media (max-width: 340px) {
        .cred-item { flex-direction: column; align-items: flex-start; }
        .login-brand h1 { font-size: 18px; }
    }
    </style>
</head>
<body>

<div class="orbs">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<div class="login-wrapper">
    {{-- Brand --}}
    @php $loginSetting = \App\Models\Setting::get(); @endphp
    <div class="login-brand">
        @if($loginSetting->logo)
        <img src="{{ asset('storage/' . $loginSetting->logo) }}" alt="Logo" style="max-height:64px;max-width:180px;object-fit:contain;margin-bottom:12px;border-radius:12px">
        @else
        <div class="brand-icon">💊</div>
        @endif
        <h1>{{ $loginSetting->pharmacy_name }}</h1>
        <p>Complete Pharmacy Management System</p>
    </div>

    {{-- Card --}}
    <div class="login-card">
        <h2>Welcome back 👋</h2>
        <p class="subtitle">Sign in to your account to continue</p>

        @if(session('success'))
        <div style="background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.4);border-radius:10px;padding:12px 14px;font-size:13px;color:#34d399;margin-bottom:18px;display:flex;align-items:center;gap:8px">
            <i class="fas fa-check-circle" style="font-size:15px"></i>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        @if($errors->any())
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input
                        type="email" id="email" name="email"
                        class="form-control"
                        placeholder="admin@pharmacy.com"
                        value="{{ old('email') }}"
                        required autofocus autocomplete="email"
                    >
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrap" style="position:relative">
                    <i class="fas fa-lock"></i>
                    <input
                        type="password" id="password" name="password"
                        class="form-control"
                        placeholder="••••••••"
                        style="padding-right: 48px"
                        required autocomplete="current-password"
                    >
                    <button type="button" onclick="togglePassLogin()" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:none;border:none;color:#94a3b8;cursor:pointer;font-size:15px;z-index:10" title="Toggle password visibility">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="remember-row">
                <label class="checkbox-label" for="remember">
                    <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember', true) ? 'checked' : '' }}>
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div style="margin-top:16px;text-align:center;font-size:13px;color:#64748b;display:flex;justify-content:center;gap:10px;flex-wrap:wrap">
            <span>Don't have an account? <a href="{{ route('register') }}" style="color:#a5b4fc;font-weight:600;text-decoration:none">Create Account</a></span>
            <span>·</span>
            <span><a href="{{ route('verify-otp') }}" style="color:#34d399;font-weight:600;text-decoration:none">🔑 Verify OTP</a></span>
        </div>

        <div class="credentials-hint">
            <div class="hint-title" style="display:flex;justify-content:space-between;align-items:center">
                <span>Demo Credentials — passwords: <strong style="color:#a5b4fc">password</strong></span>
                <button type="button" onclick="document.getElementById('demoCreds').classList.toggle('hidden')" style="background:none;border:none;color:#818cf8;font-size:11px;cursor:pointer;text-decoration:underline">Toggle</button>
            </div>
            <div id="demoCreds">
                <div class="cred-item" onclick="fillCreds('admin@pharmacy.com')" style="cursor:pointer">
                    <span class="cred-role">👑 Super Admin</span>
                    <span class="cred-email">admin@pharmacy.com</span>
                </div>
                <div class="cred-item" onclick="fillCreds('pharmacist@pharmacy.com')" style="cursor:pointer">
                    <span class="cred-role">💊 Pharmacist</span>
                    <span class="cred-email">pharmacist@pharmacy.com</span>
                </div>
                <div class="cred-item" onclick="fillCreds('cashier@pharmacy.com')" style="cursor:pointer">
                    <span class="cred-role">💵 Cashier</span>
                    <span class="cred-email">cashier@pharmacy.com</span>
                </div>
                <div class="cred-item" onclick="fillCreds('manager@pharmacy.com')" style="cursor:pointer">
                    <span class="cred-role">📦 Store Manager</span>
                    <span class="cred-email">manager@pharmacy.com</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassLogin() {
    const pwdInput = document.getElementById('password');
    const eyeIcon  = document.getElementById('eyeIcon');
    if (pwdInput.type === 'password') {
        pwdInput.type = 'text';
        eyeIcon.className = 'fas fa-eye-slash';
        eyeIcon.style.color = '#34d399';
    } else {
        pwdInput.type = 'password';
        eyeIcon.className = 'fas fa-eye';
        eyeIcon.style.color = '#a5b4fc';
    }
}

function fillCreds(email) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'password';
}
</script>
</body>
</html>
