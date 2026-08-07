<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Afzaal Pharmacy Management System — Modern, fast and secure pharmacy management.">
    <title>{{ \App\Models\Setting::get()->pharmacy_name }} — Pharmacy Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    /* ==============================
       ROOT & RESET
       ============================== */
    :root {
        --primary:      #6366f1;
        --primary-dark: #4f46e5;
        --secondary:    #10b981;
        --accent:       #f59e0b;
        --text:         #f1f5f9;
        --text-muted:   #94a3b8;
        --bg:           #0f172a;
        --bg-card:      #1e293b;
        --border:       #334155;
        --nav-h:        68px;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; font-size: 16px; }
    body {
        font-family: 'Inter', sans-serif;
        background: var(--bg);
        color: var(--text);
        line-height: 1.6;
        overflow-x: hidden;
    }
    img { max-width: 100%; display: block; }
    a { text-decoration: none; color: inherit; }
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: var(--bg); }
    ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }

    /* ==============================
       NAVBAR
       ============================== */
    .navbar {
        position: fixed; top: 0; left: 0; right: 0;
        height: var(--nav-h);
        background: rgba(15,23,42,.88);
        backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(51,65,85,.5);
        z-index: 1000;
        display: flex; align-items: center;
        padding: 0 clamp(16px, 4vw, 60px);
        justify-content: space-between;
        transition: background .3s;
    }
    .nav-brand {
        display: flex; align-items: center; gap: 12px;
        font-size: 18px; font-weight: 800;
    }
    .nav-brand .brand-icon {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        box-shadow: 0 4px 12px rgba(99,102,241,.4);
        flex-shrink: 0;
    }
    .nav-brand .brand-name { color: var(--text); }
    .nav-brand .brand-sub  { font-size: 11px; color: var(--text-muted); font-weight: 400; }
    .nav-links {
        display: flex; align-items: center; gap: 8px;
    }
    .nav-link {
        padding: 8px 16px; border-radius: 9px;
        font-size: 14px; font-weight: 500;
        color: var(--text-muted);
        transition: all .2s;
    }
    .nav-link:hover { color: var(--text); background: rgba(255,255,255,.05); }
    .nav-cta {
        padding: 9px 22px; border-radius: 10px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff; font-size: 14px; font-weight: 700;
        box-shadow: 0 4px 14px rgba(99,102,241,.4);
        transition: all .2s;
    }
    .nav-cta:hover { opacity: .9; transform: translateY(-1px); }
    /* Hamburger */
    .nav-hamburger {
        display: none;
        width: 40px; height: 40px;
        border-radius: 10px;
        background: rgba(255,255,255,.06);
        border: 1px solid var(--border);
        color: var(--text-muted);
        cursor: pointer;
        flex-direction: column; align-items: center; justify-content: center; gap: 5px;
        transition: all .2s;
    }
    .nav-hamburger:hover { color: var(--text); border-color: var(--primary); }
    .ham-line {
        display: block; width: 20px; height: 2px;
        background: currentColor; border-radius: 2px;
        transition: transform .3s, opacity .3s;
    }
    /* Mobile nav drawer */
    .mobile-nav {
        display: none;
        position: fixed; top: var(--nav-h); left: 0; right: 0;
        background: rgba(15,23,42,.97);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
        padding: 16px 20px;
        flex-direction: column; gap: 6px;
        z-index: 999;
        animation: slideDown .25s ease;
    }
    @keyframes slideDown {
        from { opacity:0; transform: translateY(-12px); }
        to   { opacity:1; transform: translateY(0); }
    }
    .mobile-nav.open { display: flex; }
    .mobile-nav .nav-link { padding: 12px 14px; font-size: 15px; border-radius: 10px; }
    .mobile-nav .nav-cta  { padding: 13px 14px; text-align: center; font-size: 15px; border-radius: 10px; margin-top: 6px; }

    /* ==============================
       HERO
       ============================== */
    .hero {
        min-height: 100vh;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        text-align: center;
        padding: calc(var(--nav-h) + 40px) clamp(16px, 5vw, 80px) 80px;
        position: relative; overflow: hidden;
    }
    /* Animated background blobs */
    .hero-blob {
        position: absolute; border-radius: 50%; filter: blur(80px);
        pointer-events: none; animation: blobFloat 8s ease-in-out infinite;
    }
    .blob-1 {
        width: clamp(200px, 40vw, 500px);
        height: clamp(200px, 40vw, 500px);
        background: rgba(99,102,241,.2);
        top: 10%; left: -10%;
        animation-delay: 0s;
    }
    .blob-2 {
        width: clamp(150px, 30vw, 400px);
        height: clamp(150px, 30vw, 400px);
        background: rgba(16,185,129,.15);
        top: 20%; right: -5%;
        animation-delay: 3s;
    }
    .blob-3 {
        width: clamp(100px, 20vw, 300px);
        height: clamp(100px, 20vw, 300px);
        background: rgba(245,158,11,.12);
        bottom: 10%; left: 30%;
        animation-delay: 6s;
    }
    @keyframes blobFloat {
        0%, 100% { transform: translate(0,0) scale(1); }
        50%       { transform: translate(20px, -20px) scale(1.05); }
    }
    .hero-content { position: relative; z-index: 1; max-width: 800px; }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(99,102,241,.15);
        border: 1px solid rgba(99,102,241,.3);
        border-radius: 999px;
        padding: 6px 16px; font-size: 13px; font-weight: 600;
        color: #a5b4fc; margin-bottom: 28px;
    }
    .hero-badge i { font-size: 10px; }
    .hero h1 {
        font-size: clamp(2rem, 6vw, 4rem);
        font-weight: 900; line-height: 1.12;
        letter-spacing: -0.02em;
        margin-bottom: 24px;
    }
    .hero h1 .gradient-text {
        background: linear-gradient(135deg, #818cf8, #34d399, #fbbf24);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .hero p {
        font-size: clamp(1rem, 2.5vw, 1.2rem);
        color: var(--text-muted); max-width: 600px; margin: 0 auto 40px;
        line-height: 1.7;
    }
    .hero-btns {
        display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;
    }
    .btn-hero-primary {
        padding: 14px 32px; border-radius: 14px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff; font-size: 16px; font-weight: 700;
        box-shadow: 0 8px 30px rgba(99,102,241,.45);
        transition: all .25s;
        display: inline-flex; align-items: center; gap: 9px;
    }
    .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(99,102,241,.55); }
    .btn-hero-outline {
        padding: 14px 32px; border-radius: 14px;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.15);
        color: var(--text); font-size: 16px; font-weight: 600;
        transition: all .25s;
        display: inline-flex; align-items: center; gap: 9px;
    }
    .btn-hero-outline:hover { background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.3); transform: translateY(-2px); }

    /* Stats strip */
    .hero-stats {
        display: flex; justify-content: center; gap: clamp(24px, 5vw, 60px);
        margin-top: 64px; flex-wrap: wrap;
    }
    .hero-stat { text-align: center; }
    .hero-stat .val {
        font-size: clamp(1.6rem, 4vw, 2.4rem);
        font-weight: 900; color: #fff;
        background: linear-gradient(135deg, #818cf8, #34d399);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .hero-stat .lbl { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

    /* ==============================
       SECTION BASE
       ============================== */
    section { padding: clamp(48px, 8vw, 100px) clamp(16px, 5vw, 80px); }
    .section-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(99,102,241,.1); border: 1px solid rgba(99,102,241,.25);
        border-radius: 999px; padding: 5px 14px;
        font-size: 12px; font-weight: 700; color: #a5b4fc;
        text-transform: uppercase; letter-spacing: .5px;
        margin-bottom: 16px;
    }
    .section-title {
        font-size: clamp(1.6rem, 4vw, 2.5rem);
        font-weight: 800; line-height: 1.2;
        letter-spacing: -0.01em; margin-bottom: 14px;
    }
    .section-sub {
        font-size: clamp(0.95rem, 2vw, 1.1rem);
        color: var(--text-muted); max-width: 560px;
        line-height: 1.7;
    }

    /* ==============================
       FEATURES
       ============================== */
    .features { background: rgba(30,41,59,.4); }
    .features-header { text-align: center; margin-bottom: clamp(32px, 6vw, 64px); }
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 280px), 1fr));
        gap: clamp(14px, 3vw, 24px);
        max-width: 1200px; margin: 0 auto;
    }
    .feat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: clamp(20px, 3vw, 28px);
        transition: all .3s;
        position: relative; overflow: hidden;
    }
    .feat-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 3px;
        border-radius: 20px 20px 0 0;
    }
    .feat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 50px rgba(0,0,0,.4); border-color: rgba(99,102,241,.3); }
    .feat-card.indigo::before { background: linear-gradient(90deg, #6366f1, #818cf8); }
    .feat-card.green::before  { background: linear-gradient(90deg, #10b981, #34d399); }
    .feat-card.yellow::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .feat-card.blue::before   { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .feat-card.red::before    { background: linear-gradient(90deg, #ef4444, #f87171); }
    .feat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
    .feat-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; margin-bottom: 18px;
    }
    .feat-card.indigo .feat-icon { background: rgba(99,102,241,.15);  color: #818cf8; }
    .feat-card.green .feat-icon  { background: rgba(16,185,129,.15);  color: #34d399; }
    .feat-card.yellow .feat-icon { background: rgba(245,158,11,.15);  color: #fbbf24; }
    .feat-card.blue .feat-icon   { background: rgba(59,130,246,.15);  color: #60a5fa; }
    .feat-card.red .feat-icon    { background: rgba(239,68,68,.15);   color: #f87171; }
    .feat-card.purple .feat-icon { background: rgba(139,92,246,.15);  color: #a78bfa; }
    .feat-title { font-size: 17px; font-weight: 700; margin-bottom: 10px; }
    .feat-desc  { font-size: 14px; color: var(--text-muted); line-height: 1.65; }

    /* ==============================
       ROLES SECTION
       ============================== */
    .roles { background: var(--bg); }
    .roles-inner { max-width: 1100px; margin: 0 auto; }
    .roles-header { margin-bottom: clamp(28px, 5vw, 56px); }
    .roles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 200px), 1fr));
        gap: clamp(12px, 2.5vw, 20px);
    }
    .role-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px; padding: 24px 20px;
        text-align: center; transition: all .3s;
    }
    .role-card:hover { transform: translateY(-3px); border-color: rgba(99,102,241,.4); box-shadow: 0 12px 40px rgba(0,0,0,.3); }
    .role-avatar {
        width: 56px; height: 56px; border-radius: 50%; margin: 0 auto 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
    }
    .role-name  { font-size: 15px; font-weight: 700; margin-bottom: 6px; }
    .role-perms { font-size: 12px; color: var(--text-muted); line-height: 1.5; }

    /* ==============================
       CTA SECTION
       ============================== */
    .cta-section {
        background: linear-gradient(135deg, rgba(99,102,241,.15) 0%, rgba(16,185,129,.1) 100%);
        border-top: 1px solid rgba(99,102,241,.2);
        border-bottom: 1px solid rgba(99,102,241,.2);
        text-align: center;
    }
    .cta-section .section-title { margin-bottom: 20px; }
    .cta-section .section-sub { margin: 0 auto 40px; }
    .cta-btns { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }

    /* ==============================
       FOOTER
       ============================== */
    footer {
        background: #080e1a;
        border-top: 1px solid rgba(51,65,85,.5);
        padding: clamp(24px, 5vw, 48px) clamp(16px, 5vw, 60px);
    }
    .footer-inner {
        max-width: 1200px; margin: 0 auto;
        display: flex; flex-wrap: wrap;
        justify-content: space-between; align-items: center; gap: 20px;
    }
    .footer-brand { display: flex; align-items: center; gap: 12px; }
    .footer-brand .brand-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex; align-items: center; justify-content: center; font-size: 16px;
    }
    .footer-brand span { font-size: 15px; font-weight: 700; }
    .footer-text { font-size: 13px; color: var(--text-muted); text-align: right; }

    /* ==============================
       RESPONSIVE
       ============================== */
    /* Tablet */
    @media (max-width: 1024px) {
        .features-grid { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
    }

    /* Tablet Portrait */
    @media (max-width: 768px) {
        .nav-links { display: none; }
        .nav-hamburger { display: flex; }
        .hero h1 { font-size: clamp(1.8rem, 7vw, 2.8rem); }
        .hero-stats { gap: 28px; }
        .roles-grid { grid-template-columns: repeat(2, 1fr); }
        .features-grid { grid-template-columns: 1fr; }
        .footer-text { text-align: left; }
        .footer-inner { flex-direction: column; align-items: flex-start; }
    }

    /* Mobile */
    @media (max-width: 520px) {
        .hero-btns { flex-direction: column; align-items: stretch; }
        .btn-hero-primary, .btn-hero-outline { justify-content: center; }
        .hero-stats { flex-direction: column; gap: 20px; align-items: center; }
        .hero-stat .val { font-size: 2rem; }
        .roles-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
        .role-card { padding: 18px 14px; }
        .cta-btns { flex-direction: column; align-items: stretch; }
        .btn-hero-primary, .btn-hero-outline { text-align: center; justify-content: center; }
        .nav-brand .brand-sub { display: none; }
        .feat-card { padding: 18px; }
        .features-grid { gap: 12px; }
        section { padding: 40px clamp(14px, 5vw, 24px); }
    }

    /* Very small */
    @media (max-width: 360px) {
        .roles-grid { grid-template-columns: 1fr; }
        .nav-brand .brand-name { font-size: 15px; }
    }
    </style>
</head>
<body>

{{-- ==============================
     NAVBAR
     ============================== --}}
<nav class="navbar" id="navbar">
    <div class="nav-brand">
        <div class="brand-icon">💊</div>
        <div>
            <div class="brand-name">{{ \App\Models\Setting::get()->pharmacy_name }}</div>
            <div class="brand-sub">Management System</div>
        </div>
    </div>

    {{-- Desktop nav --}}
    <div class="nav-links">
        <a href="#features" class="nav-link">Features</a>
        <a href="#roles" class="nav-link">Roles</a>
        @auth
            <a href="{{ url('/') }}" class="nav-cta"><i class="fas fa-th-large"></i> Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="nav-link">Log in</a>
            <a href="{{ route('login') }}" class="nav-cta"><i class="fas fa-rocket"></i> Get Started</a>
        @endauth
    </div>

    {{-- Hamburger --}}
    <button class="nav-hamburger" id="hamburger" onclick="toggleMobileNav()" aria-label="Menu">
        <span class="ham-line" id="hl1"></span>
        <span class="ham-line" id="hl2"></span>
        <span class="ham-line" id="hl3"></span>
    </button>
</nav>

{{-- Mobile nav drawer --}}
<div class="mobile-nav" id="mobileNav">
    <a href="#features" class="nav-link" onclick="closeMobileNav()"><i class="fas fa-star" style="margin-right:8px;color:#818cf8"></i> Features</a>
    <a href="#roles" class="nav-link" onclick="closeMobileNav()"><i class="fas fa-users" style="margin-right:8px;color:#34d399"></i> Roles</a>
    @auth
        <a href="{{ url('/') }}" class="nav-cta"><i class="fas fa-th-large"></i> Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="nav-link"><i class="fas fa-sign-in-alt" style="margin-right:8px;color:#60a5fa"></i> Log in</a>
        <a href="{{ route('login') }}" class="nav-cta"><i class="fas fa-rocket"></i> Get Started — Free</a>
    @endauth
</div>

{{-- ==============================
     HERO
     ============================== --}}
<section class="hero" id="home">
    <div class="hero-blob blob-1"></div>
    <div class="hero-blob blob-2"></div>
    <div class="hero-blob blob-3"></div>

    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-shield-alt"></i> Trusted Pharmacy Management
        </div>

        <h1>
            Manage Your Pharmacy<br>
            <span class="gradient-text">Smarter &amp; Faster</span>
        </h1>

        <p>
            Complete pharmacy management system with POS, inventory tracking, purchase orders, sales reports, and role-based access — all in one beautiful dashboard.
        </p>

        <div class="hero-btns">
            @auth
                <a href="{{ url('/') }}" class="btn-hero-primary">
                    <i class="fas fa-th-large"></i> Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-hero-primary">
                    <i class="fas fa-sign-in-alt"></i> Login to System
                </a>
            @endauth
            <a href="#features" class="btn-hero-outline">
                <i class="fas fa-play-circle"></i> See Features
            </a>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="val">5</div>
                <div class="lbl">User Roles</div>
            </div>
            <div class="hero-stat">
                <div class="val">30+</div>
                <div class="lbl">Features</div>
            </div>
            <div class="hero-stat">
                <div class="val">24/7</div>
                <div class="lbl">Available</div>
            </div>
            <div class="hero-stat">
                <div class="val">100%</div>
                <div class="lbl">Responsive</div>
            </div>
        </div>
    </div>
</section>

{{-- ==============================
     FEATURES
     ============================== --}}
<section class="features" id="features">
    <div class="features-header">
        <div class="section-badge"><i class="fas fa-star"></i> Features</div>
        <h2 class="section-title">Everything You Need to<br>Run Your Pharmacy</h2>
        <p class="section-sub" style="margin:0 auto">From point-of-sale to advanced reports — every tool your pharmacy needs in one powerful system.</p>
    </div>

    <div class="features-grid">
        <div class="feat-card indigo">
            <div class="feat-icon"><i class="fas fa-cash-register"></i></div>
            <div class="feat-title">Point of Sale (POS)</div>
            <div class="feat-desc">Fast, barcode-enabled sales terminal with cart management, customer search, discount & tax handling, and instant receipt printing.</div>
        </div>
        <div class="feat-card green">
            <div class="feat-icon"><i class="fas fa-pills"></i></div>
            <div class="feat-title">Medicine Inventory</div>
            <div class="feat-desc">Track medicines with expiry alerts, low stock warnings, categories, barcodes, and full price management.</div>
        </div>
        <div class="feat-card yellow">
            <div class="feat-icon"><i class="fas fa-truck"></i></div>
            <div class="feat-title">Purchase Management</div>
            <div class="feat-desc">Record supplier purchases, auto-update stock levels, and maintain a complete purchase history with supplier tracking.</div>
        </div>
        <div class="feat-card blue">
            <div class="feat-icon"><i class="fas fa-chart-bar"></i></div>
            <div class="feat-title">Reports & Analytics</div>
            <div class="feat-desc">Sales, purchases, expenses, stock levels, and profit reports — all filterable by date range with printable views.</div>
        </div>
        <div class="feat-card red">
            <div class="feat-icon"><i class="fas fa-user-shield"></i></div>
            <div class="feat-title">Role-Based Access</div>
            <div class="feat-desc">5 roles with granular permissions — Super Admin, Admin, Pharmacist, Cashier, Store Manager. Each sees only what they need.</div>
        </div>
        <div class="feat-card purple">
            <div class="feat-icon"><i class="fas fa-moon"></i></div>
            <div class="feat-title">Dark / Light / System Mode</div>
            <div class="feat-desc">Full theme switching — dark, light, and OS system mode — with instant toggle and persistent preferences saved per device.</div>
        </div>
    </div>
</section>

{{-- ==============================
     ROLES
     ============================== --}}
<section class="roles" id="roles">
    <div class="roles-inner">
        <div class="roles-header">
            <div class="section-badge"><i class="fas fa-users"></i> User Roles</div>
            <h2 class="section-title">The Right Access<br>for Every Team Member</h2>
            <p class="section-sub">Each role has tailored permissions so your team only sees what they need — keeping data secure and workflows clean.</p>
        </div>

        <div class="roles-grid">
            <div class="role-card">
                <div class="role-avatar" style="background:rgba(99,102,241,.15);color:#818cf8"><i class="fas fa-crown"></i></div>
                <div class="role-name">Super Admin</div>
                <div class="role-perms">Full system access · Users · Settings · All modules</div>
            </div>
            <div class="role-card">
                <div class="role-avatar" style="background:rgba(59,130,246,.15);color:#60a5fa"><i class="fas fa-user-tie"></i></div>
                <div class="role-name">Admin</div>
                <div class="role-perms">All operations · Medicines · Sales · Reports</div>
            </div>
            <div class="role-card">
                <div class="role-avatar" style="background:rgba(16,185,129,.15);color:#34d399"><i class="fas fa-pills"></i></div>
                <div class="role-name">Pharmacist</div>
                <div class="role-perms">POS · Medicines · Purchases · Reports</div>
            </div>
            <div class="role-card">
                <div class="role-avatar" style="background:rgba(245,158,11,.15);color:#fbbf24"><i class="fas fa-cash-register"></i></div>
                <div class="role-name">Cashier</div>
                <div class="role-perms">POS only · Sales · Customer search</div>
            </div>
            <div class="role-card">
                <div class="role-avatar" style="background:rgba(139,92,246,.15);color:#a78bfa"><i class="fas fa-boxes"></i></div>
                <div class="role-name">Store Manager</div>
                <div class="role-perms">Inventory · Purchases · Expenses · Reports</div>
            </div>
        </div>
    </div>
</section>

{{-- ==============================
     CTA
     ============================== --}}
<section class="cta-section">
    <div class="section-badge"><i class="fas fa-rocket"></i> Ready to Start?</div>
    <h2 class="section-title">Your Pharmacy, Under Control</h2>
    <p class="section-sub">Log in now and start managing your pharmacy with a modern, fast, and beautiful system.</p>
    <div class="cta-btns">
        @auth
            <a href="{{ url('/') }}" class="btn-hero-primary"><i class="fas fa-th-large"></i> Open Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn-hero-primary"><i class="fas fa-sign-in-alt"></i> Login to System</a>
        @endauth
        <a href="#features" class="btn-hero-outline"><i class="fas fa-info-circle"></i> Learn More</a>
    </div>
</section>

{{-- ==============================
     FOOTER
     ============================== --}}
<footer>
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="brand-icon">💊</div>
            <span>{{ \App\Models\Setting::get()->pharmacy_name }}</span>
        </div>
        <div class="footer-text">
            <div>© {{ date('Y') }} {{ \App\Models\Setting::get()->pharmacy_name }}. All rights reserved.</div>
            <div style="margin-top:4px;font-size:12px">Pharmacy Management System · Built with Laravel</div>
        </div>
    </div>
</footer>

<script>
/* Navbar hamburger */
let mobileNavOpen = false;
function toggleMobileNav() {
    mobileNavOpen = !mobileNavOpen;
    document.getElementById('mobileNav').classList.toggle('open', mobileNavOpen);
    // Animate hamburger to X
    const [l1, l2, l3] = ['hl1','hl2','hl3'].map(id => document.getElementById(id));
    if (mobileNavOpen) {
        l1.style.transform = 'rotate(45deg) translate(5px,5px)';
        l2.style.opacity = '0';
        l3.style.transform = 'rotate(-45deg) translate(5px,-5px)';
    } else {
        l1.style.transform = l3.style.transform = '';
        l2.style.opacity = '1';
    }
}
function closeMobileNav() {
    mobileNavOpen = false;
    document.getElementById('mobileNav').classList.remove('open');
    document.getElementById('hl1').style.transform = '';
    document.getElementById('hl2').style.opacity = '1';
    document.getElementById('hl3').style.transform = '';
}

/* Close mobile nav on outside click */
document.addEventListener('click', e => {
    const nav = document.getElementById('mobileNav');
    const btn = document.getElementById('hamburger');
    if (mobileNavOpen && !nav.contains(e.target) && !btn.contains(e.target)) {
        closeMobileNav();
    }
});

/* Close on resize to desktop */
window.addEventListener('resize', () => {
    if (window.innerWidth > 768) closeMobileNav();
});

/* Smooth scroll for anchor links */
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

/* Navbar scroll effect */
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar.style.background = window.scrollY > 40
        ? 'rgba(15,23,42,.98)'
        : 'rgba(15,23,42,.88)';
});
</script>
</body>
</html>
