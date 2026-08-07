<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP & Activate Account — {{ \App\Models\Setting::get()->pharmacy_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@600;800&display=swap" rel="stylesheet">
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
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 16px;
        position: relative;
        overflow-x: hidden;
    }

    /* ── Orbs ── */
    .orbs { position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none; }
    .orb  { position: absolute; border-radius: 50%; filter: blur(75px); opacity: .2; animation: floatOrb 9s ease-in-out infinite; }
    .orb-1 { width: 380px; height: 380px; background: #6366f1; top: -80px; left: -80px; }
    .orb-2 { width: 320px; height: 320px; background: #10b981; bottom: -60px; right: -60px; animation-delay: 3s; }
    @keyframes floatOrb {
        0%,100% { transform: translate(0,0) scale(1); }
        50%      { transform: translate(18px,-18px) scale(1.06); }
    }

    /* ── Card wrapper ── */
    .otp-wrapper {
        width: 100%;
        max-width: 440px;
        position: relative;
        z-index: 1;
        margin: 0 auto;
    }

    .otp-brand { text-align: center; margin-bottom: 24px; }
    .brand-icon {
        width: 64px; height: 64px;
        background: linear-gradient(135deg, #6366f1, #10b981);
        border-radius: 20px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 28px; margin-bottom: 12px;
        box-shadow: 0 10px 40px rgba(99,102,241,.4);
    }
    .otp-brand h1 { font-size: 24px; font-weight: 800; color: #f1f5f9; }
    .otp-brand p { font-size: 13px; color: #94a3b8; margin-top: 4px; }

    .otp-card {
        background: rgba(30,41,59,.85);
        backdrop-filter: blur(24px);
        border: 1px solid rgba(51,65,85,.8);
        border-radius: 22px;
        padding: 36px;
        box-shadow: 0 24px 80px rgba(0,0,0,.55);
    }
    .otp-card h2 { font-size: 19px; font-weight: 700; color: #f1f5f9; margin-bottom: 5px; }
    .otp-card .subtitle { font-size: 13px; color: #64748b; margin-bottom: 24px; }

    .form-group { margin-bottom: 18px; }
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
        padding: 12px 14px 12px 42px;
        color: #f1f5f9; font-size: 14px;
        font-family: 'Inter', sans-serif;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.2);
    }

    .otp-input {
        font-family: 'JetBrains Mono', monospace;
        letter-spacing: 6px;
        font-size: 20px;
        font-weight: 800;
        text-align: center;
        color: #a5b4fc;
        padding-left: 14px !important;
    }

    .btn-verify {
        width: 100%;
        padding: 13px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff; border: none; border-radius: 12px;
        font-size: 15px; font-weight: 700;
        cursor: pointer; transition: all .22s;
        box-shadow: 0 8px 24px rgba(99,102,241,.4);
        display: flex; align-items: center; justify-content: center; gap: 8px;
        margin-top: 8px;
    }
    .btn-verify:hover { opacity: .92; transform: translateY(-2px); }

    .error-msg {
        background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3);
        border-radius: 10px; padding: 10px 14px; font-size: 13px;
        color: #fca5a5; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;
    }

    .footer-link { margin-top: 20px; text-align: center; font-size: 13px; color: #64748b; }
    .footer-link a { color: #818cf8; font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>

<div class="orbs">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
</div>

<div class="otp-wrapper">
    <div class="otp-brand">
        <div class="brand-icon">🔑</div>
        <h1>{{ \App\Models\Setting::get()->pharmacy_name }}</h1>
        <p>Account OTP Verification</p>
    </div>

    <div class="otp-card">
        <h2>Verify OTP Code 🔐</h2>
        <p class="subtitle">Enter your Email and the 6-Digit OTP code provided by Super Admin to activate your account</p>

        @if($errors->any())
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('verify-otp.post') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="user@pharmacy.com" value="{{ old('email', $email) }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">6-Digit OTP Code *</label>
                <div class="input-wrap">
                    <input type="text" name="otp_code" class="form-control otp-input" placeholder="123456" maxlength="6" value="{{ old('otp_code') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">New Password (Optional / Create Password)</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" style="padding-right:48px">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="input-wrap">
                    <i class="fas fa-check-double"></i>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" style="padding-right:48px">
                </div>
            </div>

            <button type="submit" class="btn-verify">
                <i class="fas fa-key"></i> Verify OTP & Activate Account
            </button>
        </form>

        <div class="footer-link">
            Back to <a href="{{ route('login') }}">Sign In</a>
        </div>
    </div>
</div>

</body>
</html>
