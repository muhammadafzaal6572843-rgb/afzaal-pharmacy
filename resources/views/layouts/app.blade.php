<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ \App\Models\Setting::get()->pharmacy_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    /* ============================
       CSS CUSTOM PROPERTIES
       ============================ */
    :root {
        --primary:        #6366f1;
        --primary-dark:   #4f46e5;
        --primary-light:  #a5b4fc;
        --secondary:      #10b981;
        --danger:         #ef4444;
        --warning:        #f59e0b;
        --info:           #3b82f6;
        --sidebar-w:      260px;
        --sidebar-collapsed: 68px;
        --topbar-h:       64px;
        --transition:     .22s cubic-bezier(.4,0,.2,1);
    }

    /* ── DARK THEME (default) ── */
    [data-theme="dark"] {
        --bg:         #0f172a;
        --bg-card:    #1e293b;
        --bg-card2:   #263447;
        --bg-hover:   rgba(99,102,241,.06);
        --border:     #334155;
        --text:       #f1f5f9;
        --text-muted: #94a3b8;
        --text-sub:   #64748b;
        --sidebar-bg: linear-gradient(180deg, #1a1f35 0%, #0f172a 100%);
        --topbar-bg:  #1e293b;
        --shadow-sm:  0 1px 3px rgba(0,0,0,.4);
        --shadow-md:  0 4px 20px rgba(0,0,0,.5);
        --shadow-lg:  0 8px 40px rgba(0,0,0,.6);
        --input-bg:   #263447;
        --scrollbar:  #334155;
        --scrollbar-thumb: #475569;
    }

    /* ── LIGHT THEME ── */
    [data-theme="light"] {
        --bg:         #f1f5f9;
        --bg-card:    #ffffff;
        --bg-card2:   #f8fafc;
        --bg-hover:   rgba(99,102,241,.06);
        --border:     #e2e8f0;
        --text:       #0f172a;
        --text-muted: #475569;
        --text-sub:   #94a3b8;
        --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        --topbar-bg:  #ffffff;
        --shadow-sm:  0 1px 3px rgba(0,0,0,.08);
        --shadow-md:  0 4px 20px rgba(0,0,0,.1);
        --shadow-lg:  0 8px 40px rgba(0,0,0,.15);
        --input-bg:   #f8fafc;
        --scrollbar:  #e2e8f0;
        --scrollbar-thumb: #cbd5e1;
    }

    /* ============================
       BASE RESET
       ============================ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
        font-family: 'Inter', sans-serif;
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
        display: flex;
        transition: background var(--transition), color var(--transition);
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: var(--scrollbar); }
    ::-webkit-scrollbar-thumb { background: var(--scrollbar-thumb); border-radius: 99px; }

    /* ============================
       SIDEBAR
       ============================ */
    .sidebar {
        width: var(--sidebar-w);
        background: var(--sidebar-bg);
        border-right: 1px solid rgba(255,255,255,.05);
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0; left: 0; bottom: 0;
        z-index: 200;
        overflow: visible;
        transition: width var(--transition);
        box-shadow: 4px 0 24px rgba(0,0,0,.3);
    }
    .sidebar.collapsed { width: var(--sidebar-collapsed); }
    .sidebar.collapsed .nav-label,
    .sidebar.collapsed .nav-section-title,
    .sidebar.collapsed .nav-badge,
    .sidebar.collapsed .logo-text,
    .sidebar.collapsed .sidebar-user-info { display: none; }
    .sidebar.collapsed .nav-item a { justify-content: center; padding: 11px 0; }
    .sidebar.collapsed .nav-item a i { margin: 0; width: auto; }
    .sidebar.collapsed .sidebar-logo { justify-content: center; padding: 20px 0; }

    /* Logo */
    .sidebar-logo {
        padding: 20px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid rgba(255,255,255,.06);
        flex-shrink: 0;
    }
    .logo-icon {
        width: 40px; height: 40px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        box-shadow: 0 4px 12px rgba(99,102,241,.4);
    }
    .logo-text h2 { font-size: 14px; font-weight: 700; color: #f1f5f9; line-height: 1.2; }
    .logo-text span { font-size: 10px; color: #64748b; }

    /* Collapse toggle */
    .sidebar-toggle {
        position: absolute; top: 22px; right: -14px;
        width: 28px; height: 28px;
        background: var(--primary);
        border: 2px solid var(--bg);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: #fff;
        font-size: 11px;
        transition: all var(--transition);
        z-index: 10;
        box-shadow: 0 2px 8px rgba(99,102,241,.5);
    }
    .sidebar-toggle:hover { transform: scale(1.1); }
    .sidebar.collapsed .sidebar-toggle { right: -14px; }
    .sidebar.collapsed .sidebar-toggle .toggle-icon { transform: rotate(180deg); }

    /* Nav */
    .sidebar-nav { padding: 10px 0; flex: 1; overflow-y: auto; overflow-x: hidden; }
    .nav-section-title {
        padding: 10px 18px 3px;
        font-size: 10px; font-weight: 700;
        color: #475569;
        text-transform: uppercase; letter-spacing: 1px;
        white-space: nowrap;
    }
    .nav-item { margin: 1px 10px; border-radius: 10px; overflow: hidden; }
    .nav-item a {
        display: flex; align-items: center; gap: 11px;
        padding: 9px 12px;
        text-decoration: none;
        color: #94a3b8;
        font-size: 13px; font-weight: 500;
        transition: all var(--transition);
        border-radius: 10px;
        white-space: nowrap;
        position: relative;
    }
    .nav-item a:hover { background: rgba(255,255,255,.06); color: #e2e8f0; }
    .nav-item a.active {
        background: linear-gradient(135deg, rgba(99,102,241,.3), rgba(16,185,129,.1));
        color: #fff;
        box-shadow: inset 2px 0 0 var(--primary);
    }
    .nav-item a i { width: 18px; text-align: center; font-size: 14px; flex-shrink: 0; }
    .nav-label { flex: 1; }
    .nav-badge {
        margin-left: auto; flex-shrink: 0;
        background: var(--danger);
        color: #fff; font-size: 10px;
        padding: 2px 6px; border-radius: 999px; font-weight: 700;
    }

    /* Sidebar bottom — user info */
    .sidebar-user {
        padding: 14px 14px;
        border-top: 1px solid rgba(255,255,255,.06);
        display: flex; align-items: center; gap: 10px;
        flex-shrink: 0;
    }
    .sidebar-user-avatar {
        width: 34px; height: 34px; flex-shrink: 0;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 13px; color: #fff;
    }
    .sidebar-user-info .u-name { font-size: 12.5px; font-weight: 600; color: #e2e8f0; }
    .sidebar-user-info .u-role {
        font-size: 10px; color: #475569;
        background: rgba(99,102,241,.2);
        padding: 1px 7px; border-radius: 99px;
        display: inline-block; margin-top: 2px;
    }

    /* ============================
       MAIN CONTENT
       ============================ */
    .main-content {
        margin-left: var(--sidebar-w);
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        transition: margin-left var(--transition);
    }
    .main-content.expanded { margin-left: var(--sidebar-collapsed); }

    /* ============================
       TOPBAR
       ============================ */
    .topbar {
        background: var(--topbar-bg);
        border-bottom: 1px solid var(--border);
        padding: 0 24px;
        height: var(--topbar-h);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky; top: 0; z-index: 100;
        backdrop-filter: blur(12px);
        box-shadow: var(--shadow-sm);
        transition: background var(--transition), border-color var(--transition);
    }
    .topbar-left { display: flex; align-items: center; gap: 14px; }
    .topbar-title h1 { font-size: 17px; font-weight: 700; color: var(--text); }
    .topbar-title p  { font-size: 11.5px; color: var(--text-muted); margin-top: 1px; }

    .topbar-right { display: flex; align-items: center; gap: 10px; }

    /* Topbar icon buttons */
    .topbar-btn {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        color: var(--text-muted);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: all var(--transition);
        text-decoration: none;
        font-size: 14px;
        position: relative;
    }
    .topbar-btn:hover { color: var(--text); border-color: var(--primary); background: var(--bg-hover); }
    .topbar-btn .btn-badge {
        position: absolute; top: -4px; right: -4px;
        background: var(--danger); color: #fff;
        font-size: 9px; font-weight: 700;
        padding: 2px 5px; border-radius: 99px;
        min-width: 16px; text-align: center;
    }

    /* Theme Toggle */
    .theme-toggle {
        display: flex; align-items: center;
        background: var(--bg-card2);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        height: 38px;
    }
    .theme-btn {
        width: 36px; height: 100%;
        border: none;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
        transition: all var(--transition);
        position: relative;
    }
    .theme-btn:hover { color: var(--text); background: var(--bg-hover); }
    .theme-btn.active {
        background: var(--primary);
        color: #fff;
    }
    .theme-btn[title]:hover::after {
        content: attr(title);
        position: absolute;
        bottom: -28px; left: 50%; transform: translateX(-50%);
        background: #1e293b; color: #f1f5f9;
        font-size: 10px; padding: 3px 8px; border-radius: 6px;
        white-space: nowrap; pointer-events: none; z-index: 999;
    }

    /* User menu */
    .user-menu {
        display: flex; align-items: center; gap: 8px;
        cursor: pointer; position: relative;
        padding: 4px 10px 4px 4px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: var(--bg-card2);
        transition: all var(--transition);
    }
    .user-menu:hover { border-color: var(--primary); }
    .user-avatar {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 12px; color: #fff;
    }
    .user-info { line-height: 1.3; }
    .user-info .name { font-size: 12.5px; font-weight: 600; color: var(--text); }
    .user-info .role { font-size: 10.5px; color: var(--text-muted); }
    .user-dropdown {
        position: absolute;
        top: calc(100% + 8px); right: 0;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 14px;
        min-width: 190px;
        box-shadow: var(--shadow-lg);
        display: none; z-index: 300;
        overflow: hidden;
        animation: dropIn .2s ease;
    }
    @keyframes dropIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .user-menu:hover .user-dropdown { display: block; }
    .dropdown-header {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
        background: var(--bg-card2);
    }
    .dropdown-header .d-name { font-size: 13px; font-weight: 700; color: var(--text); }
    .dropdown-header .d-email { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
    .user-dropdown a, .user-dropdown button {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 16px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 13px;
        transition: all var(--transition);
        width: 100%; text-align: left;
        background: none; border: none;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
    }
    .user-dropdown a:hover { color: var(--text); background: var(--bg-hover); }
    .user-dropdown button:hover { color: var(--danger); background: rgba(239,68,68,.06); }
    .user-dropdown hr { border-color: var(--border); margin: 4px 0; }

    /* ============================
       PAGE CONTENT
       ============================ */
    .page-content { padding: 24px; flex: 1; }

    /* ============================
       ALERTS & TOASTS
       ============================ */
    .alert-bar {
        background: linear-gradient(135deg, rgba(239,68,68,.12), rgba(245,158,11,.08));
        border: 1px solid rgba(239,68,68,.25);
        border-radius: 12px;
        padding: 10px 16px;
        margin-bottom: 20px;
        font-size: 13px;
        display: flex; align-items: center; gap: 10px;
        animation: slideDown .3s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .toast-container {
        position: fixed; top: 76px; right: 20px;
        z-index: 9999;
        display: flex; flex-direction: column; gap: 8px;
    }
    .toast {
        min-width: 290px;
        padding: 14px 18px;
        border-radius: 14px;
        font-size: 13.5px; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        box-shadow: var(--shadow-lg);
        animation: slideInRight .3s ease;
        backdrop-filter: blur(8px);
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .toast-success { background: rgba(6,78,59,.9); border: 1px solid #059669; color: #6ee7b7; }
    .toast-error   { background: rgba(69,10,10,.9); border: 1px solid #dc2626; color: #fca5a5; }

    /* ============================
       CARDS
       ============================ */
    .card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        transition: background var(--transition), border-color var(--transition), box-shadow var(--transition);
    }
    .card:hover { box-shadow: var(--shadow-md); }
    .card-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border);
    }
    .card-title { font-size: 16px; font-weight: 700; color: var(--text); }

    /* ============================
       TABLE
       ============================ */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    thead { position: sticky; top: 0; z-index: 1; }
    th {
        text-align: left;
        padding: 10px 14px;
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border);
        background: var(--bg-card2);
        white-space: nowrap;
    }
    th:first-child { border-radius: 8px 0 0 0; }
    th:last-child  { border-radius: 0 8px 0 0; }
    td {
        padding: 11px 14px;
        font-size: 13.5px;
        border-bottom: 1px solid rgba(51,65,85,.4);
        vertical-align: middle;
        color: var(--text);
        transition: background var(--transition);
    }
    [data-theme="light"] td { border-bottom-color: var(--border); }
    tr:hover td { background: var(--bg-hover); }
    tr:last-child td { border-bottom: none; }

    /* ============================
       BADGES
       ============================ */
    .badge {
        display: inline-flex; align-items: center;
        padding: 3px 10px; border-radius: 999px;
        font-size: 11px; font-weight: 600;
    }
    .badge-success { background: rgba(16,185,129,.12); color: #34d399; border: 1px solid rgba(16,185,129,.25); }
    .badge-danger  { background: rgba(239,68,68,.12);  color: #f87171; border: 1px solid rgba(239,68,68,.25); }
    .badge-warning { background: rgba(245,158,11,.12); color: #fbbf24; border: 1px solid rgba(245,158,11,.25); }
    .badge-info    { background: rgba(59,130,246,.12); color: #60a5fa; border: 1px solid rgba(59,130,246,.25); }
    .badge-gray    { background: rgba(148,163,184,.1); color: var(--text-muted); border: 1px solid rgba(148,163,184,.2); }
    .badge-purple  { background: rgba(139,92,246,.12); color: #a78bfa; border: 1px solid rgba(139,92,246,.25); }

    /* ============================
       BUTTONS
       ============================ */
    .btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 10px;
        font-size: 13.5px; font-weight: 600;
        cursor: pointer; border: none;
        text-decoration: none;
        transition: all var(--transition);
        font-family: 'Inter', sans-serif;
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        box-shadow: 0 4px 14px rgba(99,102,241,.35);
    }
    .btn-primary:hover { opacity: .9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.45); }
    .btn-success { background: linear-gradient(135deg, #059669, #047857); color: #fff; }
    .btn-success:hover { opacity: .9; transform: translateY(-1px); }
    .btn-danger  { background: linear-gradient(135deg, #dc2626, #b91c1c); color: #fff; }
    .btn-danger:hover  { opacity: .9; }
    .btn-warning { background: linear-gradient(135deg, #d97706, #b45309); color: #fff; }
    .btn-outline {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text-muted);
    }
    .btn-outline:hover { border-color: var(--primary); color: var(--primary-light); background: var(--bg-hover); }
    .btn-sm  { padding: 6px 12px; font-size: 12px; border-radius: 8px; }
    .btn-icon { width: 34px; height: 34px; padding: 0; justify-content: center; border-radius: 8px; }

    /* ============================
       FORMS
       ============================ */
    .form-group { margin-bottom: 18px; }
    .form-label {
        display: block;
        font-size: 12px; font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 7px;
        text-transform: uppercase; letter-spacing: .5px;
    }
    .form-control {
        width: 100%;
        background: var(--input-bg);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 10px 14px;
        color: var(--text);
        font-size: 13.5px;
        font-family: 'Inter', sans-serif;
        transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
    }
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
    }
    .form-control option { background: var(--bg-card); color: var(--text); }
    .form-error { color: #f87171; font-size: 12px; margin-top: 5px; }

    /* ============================
       GRID
       ============================ */
    .grid   { display: grid; }
    .grid-2 { grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .grid-3 { grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .grid-4 { grid-template-columns: repeat(4, 1fr); gap: 20px; }

    /* ============================
       MODAL
       ============================ */
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,.65);
        z-index: 1000;
        display: none; align-items: center; justify-content: center;
        backdrop-filter: blur(6px);
    }
    .modal-overlay.open { display: flex; }
    .modal {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        width: 90%; max-width: 520px;
        max-height: 90vh; overflow-y: auto;
        box-shadow: var(--shadow-lg);
        animation: modalIn .25s ease;
    }
    .modal-lg { max-width: 800px; }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(.95) translateY(16px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-header {
        padding: 20px 24px 16px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .modal-header h3 { font-size: 17px; font-weight: 700; color: var(--text); }
    .modal-close {
        width: 32px; height: 32px; border-radius: 8px;
        background: var(--bg-card2); border: 1px solid var(--border);
        color: var(--text-muted); cursor: pointer;
        display: flex; align-items: center; justify-content: center; font-size: 16px;
    }
    .modal-body   { padding: 24px; }
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--border);
        display: flex; justify-content: flex-end; gap: 10px;
    }

    /* ============================
       PAGINATION
       ============================ */
    .pagination { display: flex; gap: 5px; align-items: center; flex-wrap: wrap; }
    .pagination a, .pagination span {
        min-width: 36px; height: 36px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 500;
        text-decoration: none;
    }
    .pagination a {
        background: var(--bg-card2); border: 1px solid var(--border);
        color: var(--text-muted); transition: all var(--transition);
    }
    .pagination a:hover { border-color: var(--primary); color: var(--primary-light); }
    .pagination span.active { background: var(--primary); color: #fff; }
    .pagination span.disabled { color: var(--border); }

    /* ============================
       SEARCH BAR
       ============================ */
    .search-bar { position: relative; flex: 1; }
    .search-bar input { padding-left: 40px; }
    .search-bar i {
        position: absolute; left: 14px; top: 50%;
        transform: translateY(-50%); color: var(--text-muted);
    }

    /* ============================
       PAGE HEADER
       ============================ */
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px;
    }
    .page-header h2 { font-size: 21px; font-weight: 800; color: var(--text); }
    .page-header p  { color: var(--text-muted); font-size: 13px; margin-top: 2px; }

    /* ============================
       EMPTY STATE
       ============================ */
    .empty-state {
        text-align: center; padding: 60px 20px;
        color: var(--text-muted);
    }
    .empty-state i { font-size: 48px; opacity: .25; margin-bottom: 16px; }
    .empty-state h3 { font-size: 18px; margin-bottom: 8px; color: var(--text); }
    .empty-state p  { font-size: 13px; }

    /* ============================
       RESPONSIVE — ALL BREAKPOINTS
       ============================ */

    /* ─── Desktop Large (1400px+) ─── */
    @media (min-width: 1400px) {
        .page-content { padding: 32px 36px; }
    }

    /* ─── Laptop / Desktop (1024px – 1399px) ─── */
    @media (max-width: 1399px) {
        :root { --sidebar-w: 240px; }
    }

    /* ─── Tablet Landscape (900px – 1023px) ─── */
    @media (max-width: 1023px) {
        :root { --sidebar-w: 220px; }
        .grid-4 { grid-template-columns: repeat(2, 1fr); }
        .page-content { padding: 20px; }
        .topbar { padding: 0 18px; }
        .user-info { display: none; }
    }

    /* ─── Tablet Portrait (768px – 899px) ─── */
    @media (max-width: 899px) {
        :root { --sidebar-w: 260px; }
        /* Sidebar becomes overlay drawer */
        .sidebar {
            transform: translateX(-100%);
            width: 260px !important;
            z-index: 400;
            box-shadow: 8px 0 40px rgba(0,0,0,.5);
        }
        .sidebar.mobile-open { transform: translateX(0); }
        .sidebar.collapsed { transform: translateX(-100%); width: 260px !important; }
        .sidebar.collapsed.mobile-open { transform: translateX(0); }
        /* Show ALL labels even in "collapsed" state on mobile */
        .sidebar.collapsed .nav-label,
        .sidebar.collapsed .nav-section-title,
        .sidebar.collapsed .nav-badge,
        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .sidebar-user-info { display: block; }
        .sidebar.collapsed .nav-item a { justify-content: flex-start; padding: 9px 12px; }
        .sidebar.collapsed .sidebar-logo { justify-content: flex-start; padding: 20px 18px; }
        /* Hide desktop collapse toggle on mobile */
        .sidebar-toggle { display: none; }
        /* Main content full width */
        .main-content { margin-left: 0 !important; }
        /* Mobile hamburger button */
        #mobileSidebarBtn { display: flex !important; }
        /* Grids stack */
        .grid-4 { grid-template-columns: repeat(2, 1fr); gap: 14px; }
        .grid-3 { grid-template-columns: repeat(2, 1fr); gap: 14px; }
        .grid-2 { grid-template-columns: 1fr; }
        /* Page header stacks */
        .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .page-content { padding: 16px; }
        .topbar { padding: 0 14px; height: 58px; }
        .topbar-title h1 { font-size: 15px; }
        .topbar-title p { display: none; }
        /* Backdrop overlay */
        .sidebar-backdrop {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.55);
            z-index: 399;
            backdrop-filter: blur(3px);
        }
        .sidebar-backdrop.show { display: block; }
    }

    /* ─── Mobile (max 767px) ─── */
    @media (max-width: 767px) {
        .grid-4 { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .grid-3 { grid-template-columns: 1fr 1fr; gap: 10px; }
        .grid-2 { grid-template-columns: 1fr; }
        .page-content { padding: 12px; }
        .card { padding: 16px; border-radius: 12px; }
        .card-header { flex-direction: column; align-items: flex-start; gap: 10px; }
        /* Tables: horizontal scroll with sticky first col */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-wrap table { min-width: 600px; }
        th, td { padding: 9px 10px; font-size: 12px; }
        /* Topbar compact */
        .topbar { height: 54px; padding: 0 12px; }
        .topbar-title h1 { font-size: 14px; }
        .topbar-right { gap: 6px; }
        .theme-toggle .theme-btn { width: 30px; }
        /* Buttons full-width in forms */
        .page-header .btn { width: 100%; justify-content: center; }
        /* Toasts full width */
        .toast-container { right: 10px; left: 10px; }
        .toast { min-width: unset; width: 100%; }
        /* Modals full screen */
        .modal { width: 96%; border-radius: 16px; max-height: 92vh; }
        .modal-lg { max-width: 96%; }
        /* Pagination compact */
        .pagination a, .pagination span { min-width: 30px; height: 30px; font-size: 12px; }
        /* User dropdown - wider */
        .user-dropdown { min-width: 160px; }
        /* Alert bar stacks */
        .alert-bar { flex-wrap: wrap; gap: 6px; }
        /* Form grids stack */
        .grid-2.form-grid { grid-template-columns: 1fr; }
    }

    /* ─── Mobile Small (max 420px) ─── */
    @media (max-width: 420px) {
        .grid-4 { grid-template-columns: 1fr 1fr; gap: 8px; }
        .grid-3 { grid-template-columns: 1fr 1fr; gap: 8px; }
        .topbar-btn[title="Quick POS"] { display: none; }
        .page-content { padding: 10px; }
        .card { padding: 12px; }
        th, td { padding: 8px; font-size: 11.5px; }
        .btn { padding: 8px 14px; font-size: 13px; }
        .btn-sm { padding: 5px 10px; font-size: 11.5px; }
    }
    </style>
    @stack('styles')
</head>
<body>

{{-- Mobile sidebar backdrop --}}
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeMobileSidebar()"></div>

{{-- ==============================
     SIDEBAR
     ============================== --}}
<aside class="sidebar" id="sidebar">
    {{-- Collapse toggle --}}
    <div class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()" title="Toggle Sidebar">
        <i class="fas fa-chevron-left toggle-icon"></i>
    </div>

    {{-- Logo --}}
    @php $appSetting = \App\Models\Setting::get(); @endphp
    <div class="sidebar-logo">
        @if($appSetting->logo)
            <img src="{{ asset('storage/' . $appSetting->logo) }}" alt="Logo" style="width:40px;height:40px;object-fit:cover;border-radius:10px;border:1px solid rgba(255,255,255,.15);flex-shrink:0">
        @else
            <div class="logo-icon">💊</div>
        @endif
        <div class="logo-text">
            <h2>{{ $appSetting->pharmacy_name }}</h2>
            <span>Management System</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        {{-- Main --}}
        @can('view dashboard')
        <div class="nav-section-title">Main</div>
        <div class="nav-item">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
                <i class="fas fa-th-large"></i>
                <span class="nav-label">Dashboard</span>
            </a>
        </div>
        @endcan
        @can('access pos')
        <div class="nav-item">
            <a href="{{ route('pos.index') }}" class="{{ request()->routeIs('pos.*') ? 'active' : '' }}" title="Point of Sale">
                <i class="fas fa-cash-register"></i>
                <span class="nav-label">Point of Sale</span>
            </a>
        </div>
        @endcan

        {{-- Inventory --}}
        @canany(['view medicines', 'view categories'])
        <div class="nav-section-title" style="margin-top:6px">Inventory</div>
        @endcanany
        @can('view medicines')
        <div class="nav-item">
            <a href="{{ route('medicines.index') }}" class="{{ request()->routeIs('medicines.*') ? 'active' : '' }}" title="Medicines">
                <i class="fas fa-pills"></i>
                <span class="nav-label">Medicines</span>
                @php $expired = \App\Models\Medicine::active()->expired()->count() @endphp
                @if($expired > 0)
                    <span class="nav-badge">{{ $expired }}</span>
                @endif
            </a>
        </div>
        @endcan
        @can('view categories')
        <div class="nav-item">
            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}" title="Categories">
                <i class="fas fa-tags"></i>
                <span class="nav-label">Categories</span>
            </a>
        </div>
        @endcan

        {{-- Transactions --}}
        @canany(['view sales', 'view purchases', 'view expenses'])
        <div class="nav-section-title" style="margin-top:6px">Transactions</div>
        @endcanany
        @can('view sales')
        <div class="nav-item">
            <a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales.*') ? 'active' : '' }}" title="Sales">
                <i class="fas fa-shopping-cart"></i>
                <span class="nav-label">Sales</span>
            </a>
        </div>
        @endcan
        @can('view purchases')
        <div class="nav-item">
            <a href="{{ route('purchases.index') }}" class="{{ request()->routeIs('purchases.*') ? 'active' : '' }}" title="Purchases">
                <i class="fas fa-truck"></i>
                <span class="nav-label">Purchases</span>
            </a>
        </div>
        @endcan
        @can('view expenses')
        <div class="nav-item">
            <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}" title="Expenses">
                <i class="fas fa-wallet"></i>
                <span class="nav-label">Expenses</span>
            </a>
        </div>
        @endcan

        {{-- People --}}
        @canany(['view suppliers', 'view customers'])
        <div class="nav-section-title" style="margin-top:6px">People</div>
        @endcanany
        @can('view suppliers')
        <div class="nav-item">
            <a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}" title="Suppliers">
                <i class="fas fa-industry"></i>
                <span class="nav-label">Suppliers</span>
            </a>
        </div>
        @endcan
        @can('view customers')
        <div class="nav-item">
            <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}" title="Customers">
                <i class="fas fa-users"></i>
                <span class="nav-label">Customers</span>
            </a>
        </div>
        @endcan

        {{-- System --}}
        <div class="nav-section-title" style="margin-top:6px">System</div>
        @can('view reports')
        <div class="nav-item">
            <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}" title="Reports">
                <i class="fas fa-chart-bar"></i>
                <span class="nav-label">Reports</span>
            </a>
        </div>
        @endcan
        @can('view users')
        <div class="nav-item">
            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}" title="Users">
                <i class="fas fa-user-shield"></i>
                <span class="nav-label">Users</span>
            </a>
        </div>
        @endcan
        @can('view settings')
        <div class="nav-item">
            <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}" title="Settings">
                <i class="fas fa-cog"></i>
                <span class="nav-label">Settings</span>
            </a>
        </div>
        @endcan
    </nav>

    {{-- Sidebar bottom user card --}}
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="sidebar-user-info">
            <div class="u-name">{{ Str::limit(auth()->user()->name, 18) }}</div>
            <span class="u-role">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</span>
        </div>
    </div>

    {{-- Sidebar Logout Button --}}
    <div style="padding:10px 14px 16px;border-top:1px solid rgba(255,255,255,.06)">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-logout-btn" title="Logout Account" style="width:100%;display:flex;align-items:center;gap:10px;padding:9px 12px;background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);color:#f87171;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;font-family:'Inter',sans-serif">
                <i class="fas fa-sign-out-alt" style="width:18px;text-align:center"></i>
                <span class="nav-label">Logout Account</span>
            </button>
        </form>
    </div>
</aside>

{{-- ==============================
     MAIN CONTENT
     ============================== --}}
<div class="main-content" id="mainContent">

    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="topbar-left">
            {{-- Universal sidebar toggle --}}
            <button class="topbar-btn" id="sidebarCollapseBtn" onclick="toggleSidebarMenu()" title="Toggle Sidebar Menu">
                <i class="fas fa-bars"></i>
            </button>
            <div class="topbar-title">
                <h1>@yield('title', 'Dashboard')</h1>
                <p>{{ now()->format('l, F d Y') }}</p>
            </div>
        </div>

        <div class="topbar-right">
            {{-- Quick: POS --}}
            @can('access pos')
            <a href="{{ route('pos.index') }}" class="topbar-btn" title="Quick POS">
                <i class="fas fa-cash-register"></i>
            </a>
            @endcan

            {{-- Notification Bell (expired medicines) --}}
            @php $notifCount = \App\Models\Medicine::active()->expired()->count() + \App\Models\Medicine::active()->lowStock()->count(); @endphp
            @can('view medicines')
            <a href="{{ route('medicines.index') }}" class="topbar-btn" title="{{ $notifCount }} alerts">
                <i class="fas fa-bell"></i>
                @if($notifCount > 0)
                    <span class="btn-badge">{{ $notifCount }}</span>
                @endif
            </a>
            @endcan

            {{-- Theme Toggle: Dark / Light --}}
            <div class="theme-toggle" role="group" aria-label="Theme">
                <button class="theme-btn" id="theme-dark"  onclick="setTheme('dark')"  title="Dark Mode"><i class="fas fa-moon"></i></button>
                <button class="theme-btn" id="theme-light" onclick="setTheme('light')" title="Light Mode"><i class="fas fa-sun"></i></button>
            </div>

            {{-- User Menu --}}
            <div class="user-menu">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <div class="name">{{ Str::limit(auth()->user()->name, 14) }}</div>
                    <div class="role">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</div>
                </div>
                <i class="fas fa-chevron-down" style="font-size:10px;color:var(--text-muted)"></i>
                <div class="user-dropdown">
                    <div class="dropdown-header">
                        <div class="d-name">{{ auth()->user()->name }}</div>
                        <div class="d-email">{{ auth()->user()->email }}</div>
                    </div>
                    @can('view settings')
                    <a href="{{ route('settings.index') }}"><i class="fas fa-cog"></i> Settings</a>
                    @endcan
                    <hr>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- PAGE CONTENT --}}
    <main class="page-content">
        {{-- Toast messages --}}
        @if(session('success'))
            <div class="toast-container">
                <div class="toast toast-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast-container">
                <div class="toast toast-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- Alert bar --}}
        @php
            $expiredCount  = \App\Models\Medicine::active()->expired()->count();
            $lowStockCount = \App\Models\Medicine::active()->lowStock()->count();
        @endphp
        @if(($expiredCount > 0 || $lowStockCount > 0) && auth()->user()->can('view medicines'))
        <div class="alert-bar">
            <i class="fas fa-exclamation-triangle" style="color:#f59e0b"></i>
            @if($expiredCount > 0)
                <strong>{{ $expiredCount }}</strong> expired medicine(s).
            @endif
            @if($expiredCount > 0 && $lowStockCount > 0) &nbsp;|&nbsp; @endif
            @if($lowStockCount > 0)
                <strong>{{ $lowStockCount }}</strong> low stock medicine(s).
            @endif
            <a href="{{ route('medicines.index') }}" style="color:var(--warning);margin-left:auto;font-size:12px;font-weight:600">View →</a>
        </div>
        @endif

        @yield('content')
    </main>
</div>

{{-- ==============================
     SCRIPTS
     ============================== --}}
<script>
/* ── THEME SYSTEM ── */
const THEME_KEY = 'pharmacy_theme';

function applyTheme(mode) {
    const theme = (mode === 'light') ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', theme);

    ['dark', 'light'].forEach(m => {
        const btn = document.getElementById('theme-' + m);
        if (btn) btn.classList.toggle('active', m === theme);
    });
}

function setTheme(mode) {
    localStorage.setItem(THEME_KEY, mode);
    applyTheme(mode);
}

// Init on load
(function() {
    let saved = localStorage.getItem(THEME_KEY);
    if (saved !== 'light' && saved !== 'dark') saved = 'dark';
    applyTheme(saved);
})();

/* ── SIDEBAR COLLAPSE ── */
const SIDEBAR_KEY = 'pharmacy_sidebar';

function toggleSidebar() {
    const sb   = document.getElementById('sidebar');
    const mc   = document.getElementById('mainContent');
    const icon = document.querySelector('.toggle-icon');
    const collapsed = sb.classList.toggle('collapsed');
    mc.classList.toggle('expanded', collapsed);
    icon.style.transform = collapsed ? 'rotate(180deg)' : '';
    localStorage.setItem(SIDEBAR_KEY, collapsed ? '1' : '0');
}

// Restore sidebar state (desktop only)
(function() {
    if (window.innerWidth > 899) {
        if (localStorage.getItem(SIDEBAR_KEY) === '1') {
            document.getElementById('sidebar').classList.add('collapsed');
            document.getElementById('mainContent').classList.add('expanded');
            const icon = document.querySelector('.toggle-icon');
            if (icon) icon.style.transform = 'rotate(180deg)';
        }
    }
})();

/* ── MOBILE SIDEBAR ── */
function isMobileView() { return window.innerWidth <= 899; }

function openMobileSidebar() {
    document.getElementById('sidebar').classList.add('mobile-open');
    document.getElementById('sidebarBackdrop').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeMobileSidebar() {
    document.getElementById('sidebar').classList.remove('mobile-open');
    document.getElementById('sidebarBackdrop').classList.remove('show');
    document.body.style.overflow = '';
}

function toggleMobileSidebar() {
    const sb = document.getElementById('sidebar');
    if (sb.classList.contains('mobile-open')) {
        closeMobileSidebar();
    } else {
        openMobileSidebar();
    }
}

function toggleSidebarMenu() {
    if (isMobileView()) {
        toggleMobileSidebar();
    } else {
        toggleSidebar();
    }
}

// Close sidebar on nav link click (mobile)
document.querySelectorAll('.nav-item a').forEach(link => {
    link.addEventListener('click', () => {
        if (isMobileView()) closeMobileSidebar();
    });
});

// Handle resize: reset mobile state when going desktop
window.addEventListener('resize', () => {
    if (!isMobileView()) {
        closeMobileSidebar();
    }
});

/* ── SWIPE TO OPEN SIDEBAR (touch devices) ── */
let touchStartX = 0;
document.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
document.addEventListener('touchend', e => {
    if (!isMobileView()) return;
    const dx = e.changedTouches[0].clientX - touchStartX;
    const sb = document.getElementById('sidebar');
    // Swipe right from left edge → open
    if (dx > 60 && touchStartX < 30 && !sb.classList.contains('mobile-open')) {
        openMobileSidebar();
    }
    // Swipe left → close
    if (dx < -60 && sb.classList.contains('mobile-open')) {
        closeMobileSidebar();
    }
}, { passive: true });

/* ── TOASTS AUTO-DISMISS ── */
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('.toast').forEach(t => {
            t.style.transition = 'all .4s ease';
            t.style.opacity = '0';
            t.style.transform = 'translateX(30px)';
            setTimeout(() => t.closest('.toast-container')?.remove(), 400);
        });
    }, 4000);
});

/* ── MODAL HELPERS ── */
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.addEventListener('click', e => {
    if (e.target.classList.contains('modal-overlay')) e.target.classList.remove('open');
});
</script>
@stack('scripts')
</body>
</html>
