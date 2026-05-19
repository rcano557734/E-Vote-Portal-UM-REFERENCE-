<!DOCTYPE html>
<html lang="en" data-theme="light" data-fontsize="md" data-contrast="normal" data-motion="normal">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UM E-Vote | @yield('title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,700;12..96,800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Apply saved preferences BEFORE first paint to avoid flash --}}
    <script>
        (function () {
            try {
                var p = JSON.parse(localStorage.getItem('um_prefs') || '{}');
                var h = document.documentElement;
                if (p.theme)    h.setAttribute('data-theme',    p.theme);
                if (p.fontSize) h.setAttribute('data-fontsize', p.fontSize);
                if (p.contrast) h.setAttribute('data-contrast', p.contrast);
                if (p.motion)   h.setAttribute('data-motion',   p.motion);
            } catch(e) {}
        })();
    </script>

    <style>
        /* ═══════════════════════════════════════════════════════════
           DESIGN TOKENS — Light (default)
        ═══════════════════════════════════════════════════════════ */
        :root {
            --um-maroon:        #8a1538;
            --um-maroon-dark:   #5c0d24;
            --um-maroon-light:  #fff0f2;
            --um-gold:          #fdb813;
            --um-gold-dark:     #d97706;

            --bg-main:          #f1f5f9;
            --bg-surface:       #ffffff;
            --bg-surface-alt:   #f8fafc;
            --bg-sidebar:       #ffffff;
            --border-col:       #e2e8f0;
            --border-subtle:    #f1f5f9;
            --text-heading:     #0f172a;
            --text-body:        #334155;
            --text-muted:       #64748b;
            --text-faint:       #94a3b8;
            --shadow-sm:        0 1px 6px rgba(0,0,0,0.05);
            --shadow-md:        0 4px 20px rgba(0,0,0,0.07);
            --input-bg:         #f8fafc;
            --header-bg:        rgba(255,255,255,0.93);

            /* Font scale — md default */
            --fs-xs:   11px;
            --fs-sm:   13px;
            --fs-base: 15px;
            --fs-lg:   17px;
            --fs-xl:   20px;
            --fs-2xl:  24px;
            --fs-3xl:  32px;
            --fs-4xl:  40px;
        }

        /* ── Dark Mode ────────────────────────────────────────── */
        [data-theme="dark"] {
            --bg-main:         #0f172a;
            --bg-surface:      #1e293b;
            --bg-surface-alt:  #162032;
            --bg-sidebar:      #1a2744;
            --border-col:      #2d3f5a;
            --border-subtle:   #1f3050;
            --text-heading:    #f1f5f9;
            --text-body:       #cbd5e1;
            --text-muted:      #94a3b8;
            --text-faint:      #64748b;
            --shadow-sm:       0 1px 6px rgba(0,0,0,0.35);
            --shadow-md:       0 4px 20px rgba(0,0,0,0.45);
            --input-bg:        #1e3050;
            --header-bg:       rgba(26,39,68,0.96);
            --um-maroon-light: #3d1020;
        }

        /* ── High Contrast (Light) ────────────────────────────── */
        [data-contrast="high"] {
            --border-col:   #000;
            --text-heading: #000;
            --text-body:    #111;
            --text-muted:   #333;
            --bg-surface:   #fff;
            --bg-main:      #e8e8e8;
        }
        [data-theme="dark"][data-contrast="high"] {
            --border-col:   #fff;
            --text-heading: #fff;
            --text-body:    #eee;
            --text-muted:   #ccc;
            --bg-surface:   #000;
            --bg-main:      #111;
        }

        /* ── Font Sizes ───────────────────────────────────────── */
        [data-fontsize="sm"] { --fs-xs:10px; --fs-sm:12px; --fs-base:13px; --fs-lg:15px; --fs-xl:17px; --fs-2xl:20px; --fs-3xl:26px; --fs-4xl:32px; }
        [data-fontsize="md"] { /* defaults above */ }
        [data-fontsize="lg"] { --fs-xs:12px; --fs-sm:14px; --fs-base:17px; --fs-lg:19px; --fs-xl:22px; --fs-2xl:27px; --fs-3xl:36px; --fs-4xl:46px; }
        [data-fontsize="xl"] { --fs-xs:14px; --fs-sm:16px; --fs-base:20px; --fs-lg:22px; --fs-xl:26px; --fs-2xl:32px; --fs-3xl:42px; --fs-4xl:54px; }

        /* ── Reduced Motion ───────────────────────────────────── */
        [data-motion="reduce"] *, [data-motion="reduce"] *::before, [data-motion="reduce"] *::after {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }

        /* ═══════════════════════════════════════════════════════════
           BASE
        ═══════════════════════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            font-size:   var(--fs-base);
            line-height: 1.6;
            background:  var(--bg-main);
            color:       var(--text-body);
            margin: 0; overflow-x: hidden;
            transition: background .3s, color .3s;
        }

        /* ═══════════════════════════════════════════════════════════
           LAYOUT
        ═══════════════════════════════════════════════════════════ */
        .app-wrapper  { display: flex; min-height: 100vh; }
        .main-content { flex:1; margin-left:272px; display:flex; flex-direction:column; min-height:100vh; transition: margin-left .3s ease; }
        .content-area { padding: clamp(18px,3.5vw,48px) clamp(14px,3.5vw,52px); flex:1; max-width:1420px; width:100%; margin:0 auto; }

        /* ═══════════════════════════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════════════════════════ */
        .sidebar {
            width: 272px;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-col);
            display: flex; flex-direction: column;
            position: fixed; height: 100vh;
            z-index: 300; overflow: hidden; overflow-y: auto;
            box-shadow: 4px 0 24px rgba(0,0,0,0.04);
            transition: transform .3s ease, background .3s, border-color .3s, width .3s;
        }
        .sidebar-brand {
            padding: 19px 17px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex; align-items: center; gap: 11px;
            text-decoration: none; flex-shrink: 0;
            transition: border-color .3s;
        }
        .sidebar-menu { padding: 14px 11px; flex: 1; overflow-y: auto; min-height: 0; }
        .menu-label {
            font-size: var(--fs-xs); font-weight: 800; color: var(--text-faint);
            text-transform: uppercase; letter-spacing: .12em; margin: 8px 0 10px 13px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 15px; color: var(--text-muted);
            text-decoration: none; font-weight: 600; font-size: var(--fs-sm);
            border-radius: 11px; transition: all .2s; margin-bottom: 3px;
            border: 1.5px solid transparent;
        }
        .nav-item:hover  { background: var(--bg-surface-alt); color: var(--text-heading); border-color: var(--border-col); }
        .nav-item.active { background: var(--um-maroon-light); color: var(--um-maroon); border-color: #fbcfe8; font-weight: 700; }
        .nav-item i      { font-size: 15px; flex-shrink: 0; }

        .role-badge    { display:inline-block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; padding:2px 7px; border-radius:20px; margin-top:2px; }
        .role-admin    { background:#fef2f2; color:var(--um-maroon); border:1px solid #fecaca; }
        .role-auditor  { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
        .role-voter    { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; }

        .sidebar-footer { padding:13px 11px; border-top:1px solid var(--border-subtle); background:var(--bg-surface-alt); flex-shrink:0; transition:background .3s, border-color .3s; position:sticky; bottom:0; z-index:10; }
        .user-card {
            display:flex; align-items:center; gap:10px; padding:10px 12px;
            border-radius:11px; background:var(--bg-surface); border:1px solid var(--border-col);
            margin-bottom:9px; cursor:default; transition: background .3s, border-color .3s;
        }
        .user-avatar {
            width:36px; height:36px; background:var(--um-maroon-light); color:var(--um-maroon);
            border-radius:50%; display:flex; align-items:center; justify-content:center;
            font-size:15px; flex-shrink:0;
        }
        .logout-btn {
            width:100%; background:transparent; border:1.5px solid #fecaca;
            display:flex; align-items:center; justify-content:center; gap:7px;
            padding:9px 12px; color:#ef4444; font-weight:700; font-size:var(--fs-sm);
            border-radius:11px; cursor:pointer; transition:all .2s; font-family:'DM Sans',sans-serif;
        }
        .logout-btn:hover { background:#fef2f2; border-color:#fca5a5; transform:translateY(-1px); }

        /* ═══════════════════════════════════════════════════════════
           TOP HEADER
        ═══════════════════════════════════════════════════════════ */
        .top-header {
            background: var(--header-bg);
            backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border-col);
            padding: 14px clamp(14px,3.5vw,48px);
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top:0; z-index:200;
            box-shadow: var(--shadow-sm);
            transition: background .3s, border-color .3s;
            gap: 12px; flex-wrap: wrap;
        }

        .status-banner { font-size:var(--fs-xs); font-weight:700; letter-spacing:.05em; text-transform:uppercase; padding:5px 11px; border-radius:20px; white-space:nowrap; }
        .status-pending   { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
        .status-active    { background:#ecfdf5; color:#15803d; border:1px solid #a7f3d0; }
        .status-closed    { background:#fffbeb; color:#b45309; border:1px solid #fde68a; }
        .status-certified { background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; }
        .status-published { background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe; }

        /* ═══════════════════════════════════════════════════════════
           MOBILE SIDEBAR TOGGLE BUTTON
        ═══════════════════════════════════════════════════════════ */
        .sidebar-toggle {
            display: none;
            background: var(--bg-surface); border: 1px solid var(--border-col);
            border-radius: 9px; padding: 7px 9px;
            color: var(--text-heading); cursor: pointer; font-size: 18px; line-height:1;
            flex-shrink: 0; transition: background .3s, border-color .3s;
        }

        /* ═══════════════════════════════════════════════════════════
           GLOBAL DARK-AWARE OVERRIDES
        ═══════════════════════════════════════════════════════════ */
        .dash-card, .profile-card {
            background: var(--bg-surface) !important;
            border-color: var(--border-col) !important;
            color: var(--text-body);
            transition: background .3s, border-color .3s, color .3s;
        }
        .table { color: var(--text-body); }
        .table th { color: var(--text-muted); border-color: var(--border-col); }
        .table td { border-color: var(--border-col); }
        .table-hover tbody tr:hover { background: var(--bg-surface-alt); }
        .form-control, .form-select, .input-um {
            background: var(--input-bg) !important;
            border-color: var(--border-col) !important;
            color: var(--text-heading) !important;
            transition: background .3s, border-color .3s, color .3s;
        }
        .form-control:focus, .form-select:focus, .input-um:focus {
            border-color: var(--um-maroon) !important;
            box-shadow: 0 0 0 4px rgba(138,21,56,.12) !important;
            background: var(--bg-surface) !important;
        }
        .text-muted    { color: var(--text-muted) !important; }
        .text-dark     { color: var(--text-heading) !important; }
        .bg-light      { background: var(--bg-surface-alt) !important; }
        .border        { border-color: var(--border-col) !important; }
        .badge.bg-light { background: var(--bg-surface-alt) !important; color: var(--text-body) !important; }
        hr             { border-color: var(--border-col); }
        .alert-info    { background: var(--bg-surface-alt); border-color: var(--border-col); color: var(--text-body); }

        /* ═══════════════════════════════════════════════════════════
           SCROLLBAR
        ═══════════════════════════════════════════════════════════ */
        ::-webkit-scrollbar       { width:5px; height:5px; }
        ::-webkit-scrollbar-track { background: var(--bg-main); }
        ::-webkit-scrollbar-thumb { background: var(--text-faint); border-radius:3px; }

        /* ═══════════════════════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════════════════════ */

        /* Large desktop 1400px+ */
        @media (min-width: 1400px) {
            .sidebar      { width: 288px; }
            .main-content { margin-left: 288px; }
        }

        /* Tablet: 768px–1199px */
        @media (max-width: 1199px) {
            .sidebar        { transform: translateX(-272px); }
            .sidebar.open   { transform: translateX(0); box-shadow: 4px 0 40px rgba(0,0,0,0.25); }
            .main-content   { margin-left: 0 !important; }
            .sidebar-toggle { display: flex; align-items: center; }
        }

        /* Mobile: ≤767px */
        @media (max-width: 767px) {
            .content-area  { padding: 14px 12px; }
            .top-header    { padding: 11px 14px; gap: 8px; }
            .header-sub    { display: none; }
            .status-banner { font-size: 10px; padding: 4px 8px; }
        }

        /* Very small: ≤400px */
        @media (max-width: 400px) {
            .top-header { flex-wrap: nowrap; }
        }

        /* Sidebar overlay */
        #sidebarOverlay {
            display:none; position:fixed; inset:0;
            background:rgba(0,0,0,0.5);
            z-index:299; backdrop-filter:blur(2px);
            transition: opacity .3s;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="app-wrapper">

    {{-- ═══════════ SIDEBAR ════════════════════════════════════════════ --}}
    <aside class="sidebar" id="sidebar" aria-label="Main navigation">

        <a href="{{ route('candidates.index') }}" class="sidebar-brand">
            <div style="width:33px;height:33px;background:linear-gradient(135deg,var(--um-maroon),var(--um-maroon-dark));border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--um-gold);font-weight:900;font-family:'Bricolage Grotesque';font-size:15px;box-shadow:0 3px 9px rgba(138,21,56,0.3);flex-shrink:0;">UM</div>
            <span style="font-family:'Bricolage Grotesque';font-weight:800;font-size:16.5px;color:var(--text-heading);letter-spacing:-0.02em;line-height:1.1;">E-Vote <span style="color:var(--um-maroon);">Portal</span></span>
        </a>

        <nav class="sidebar-menu" aria-label="Dashboard navigation">
            <div class="menu-label">Main Menu</div>

            <a href="{{ route('candidates.index') }}"
               class="nav-item {{ request()->routeIs('candidates.index') ? 'active' : '' }}"
               aria-current="{{ request()->routeIs('candidates.index') ? 'page' : 'false' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            @if(auth()->user()->role_id === 1)
                <a href="{{ route('candidates.create') }}"
                   class="nav-item {{ request()->routeIs('candidates.create') ? 'active' : '' }}">
                    <i class="bi bi-person-plus-fill"></i> Register Partylist
                </a>
                <a href="{{ route('access') }}"
                   class="nav-item {{ request()->routeIs('access') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock-fill"></i> Access Control
                </a>
                <a href="{{ route('election.archives') }}"
                   class="nav-item {{ request()->routeIs('election.archives') ? 'active' : '' }}">
                    <i class="bi bi-archive-fill"></i> Election Archives
                </a>
            @elseif(auth()->user()->role_id === 2)
                <a href="{{ route('ledger') }}"
                   class="nav-item {{ request()->routeIs('ledger') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> Audit Ledger
                </a>
            @else
                <a href="{{ route('history') }}"
                   class="nav-item {{ request()->routeIs('history') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Voting History
                </a>
            @endif

            <div class="menu-label" style="margin-top:22px;">Account</div>
            <a href="{{ route('profile.show') }}"
               class="nav-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i> Profile &amp; Settings
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar"><i class="bi bi-person-fill"></i></div>
                <div style="line-height:1.3;overflow:hidden;min-width:0;">
                    <div class="fw-bold text-truncate" style="font-size:var(--fs-sm);color:var(--text-heading);">{{ auth()->user()->name }}</div>
                    <div class="text-truncate" style="font-size:11px;color:var(--text-muted);">{{ auth()->user()->email }}</div>
                    @php $rc = match(auth()->user()->role_id){1=>'role-admin',2=>'role-auditor',default=>'role-voter'}; @endphp
                    <span class="role-badge {{ $rc }}">{{ auth()->user()->role->role_name ?? 'Voter' }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="m-0 confirm-form"
                  data-title="Sign Out?" data-text="Are you sure you want to log out of UM E-Vote?"
                  data-btn="Yes, Sign Out" data-color="#ef4444">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <div id="sidebarOverlay" onclick="closeSidebar()" role="presentation"></div>

    {{-- ═══════════ MAIN CONTENT ═══════════════════════════════════════ --}}
    <main class="main-content" id="mainContent">

        <header class="top-header" role="banner">
            <div class="d-flex align-items-center gap-2">
                <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle navigation menu">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <div style="font-family:'Bricolage Grotesque';font-weight:800;color:var(--text-heading);font-size:var(--fs-lg);line-height:1.2;">
                        Welcome, <span style="color:var(--um-maroon);">{{ explode(' ', auth()->user()->name)[0] }}</span>
                    </div>
                    <div class="header-sub" style="font-size:var(--fs-xs);color:var(--text-muted);font-weight:500;margin-top:1px;">
                        University of Mindanao &bull; {{ now()->format('l, F j, Y') }}
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                @php
                    $__el  = \App\Models\Election::where('status','active')->first() ?? \App\Models\Election::latest()->first();
                    $__st  = $__el ? $__el->status : 'pending';
                @endphp
                <span class="status-banner status-{{ $__st }}">
                    <i class="bi bi-dot" style="font-size:15px;vertical-align:middle;"></i>{{ strtoupper($__st) }}
                </span>
            </div>
        </header>

        <div class="content-area" role="main">
            @yield('content')
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ═══════════════════════════════════════════════════════════════════════
   SIDEBAR
═══════════════════════════════════════════════════════════════════════ */
function toggleSidebar() {
    const s  = document.getElementById('sidebar');
    const ov = document.getElementById('sidebarOverlay');
    const open = s.classList.toggle('open');
    ov.style.display = open ? 'block' : 'none';
    document.body.style.overflow = open ? 'hidden' : '';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });

/* ═══════════════════════════════════════════════════════════════════════
   PREFERENCES ENGINE  (dark mode, font size, contrast, motion)
   All prefs stored in localStorage → persist across sessions + logout
═══════════════════════════════════════════════════════════════════════ */
const Prefs = {
    _key: 'um_prefs',
    get()          { try { return JSON.parse(localStorage.getItem(this._key) || '{}'); } catch(e){ return {}; } },
    set(k, v)      { const p = this.get(); p[k] = v; localStorage.setItem(this._key, JSON.stringify(p)); },
    apply(p) {
        const h = document.documentElement;
        h.setAttribute('data-theme',    p.theme    || 'light');
        h.setAttribute('data-fontsize', p.fontSize || 'md');
        h.setAttribute('data-contrast', p.contrast || 'normal');
        h.setAttribute('data-motion',   p.motion   || 'normal');
    }
};

/* Public API — called from profile page buttons */
function setTheme(v)    { Prefs.set('theme',    v); Prefs.apply(Prefs.get()); syncPrefUI(); }
function setFontSize(v) { Prefs.set('fontSize', v); Prefs.apply(Prefs.get()); syncPrefUI(); }
function setContrast(v) { Prefs.set('contrast', v); Prefs.apply(Prefs.get()); syncPrefUI(); }
function setMotion(v)   { Prefs.set('motion',   v); Prefs.apply(Prefs.get()); syncPrefUI(); }
function toggleTheme()  { const c = Prefs.get().theme || 'light'; setTheme(c === 'dark' ? 'light' : 'dark'); }

/* Sync visual state of all [data-pref-key][data-pref-val] elements */
function syncPrefUI() {
    const p = Prefs.get();
    document.querySelectorAll('[data-pref-key]').forEach(el => {
        const active = p[el.dataset.prefKey] === el.dataset.prefVal
            || (!p[el.dataset.prefKey] && el.dataset.prefVal === 'default');
        el.classList.toggle('pref-active', active);
        el.setAttribute('aria-pressed', String(active));
    });
}

/* Boot */
Prefs.apply(Prefs.get());

/* ═══════════════════════════════════════════════════════════════════════
   SWEETALERT SYSTEM
═══════════════════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {

    @if(session('error'))
        Swal.fire({ icon:'error', title:'Action Denied', text:@json(session('error')), confirmButtonColor:'#8a1538' });
    @endif
    @if(session('success'))
        Swal.fire({ icon:'success', title:'Success', text:@json(session('success')), confirmButtonColor:'#8a1538', timer:3500, timerProgressBar:true });
    @endif
    @if(session('voted_success'))
        Swal.fire({ icon:'success', title:'🗳️ Ballot Sealed!', text:@json(session('voted_success')), confirmButtonColor:'#8a1538' });
    @endif

    /* Confirm forms */
    document.querySelectorAll('.confirm-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: this.dataset.title || 'Are you sure?',
                text:  this.dataset.text  || 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: this.dataset.color || '#8a1538',
                cancelButtonColor:  '#64748b',
                confirmButtonText:  this.dataset.btn || 'Yes, proceed',
                cancelButtonText:   'Cancel'
            }).then(r => { if (r.isConfirmed) this.submit(); });
        });
    });

    syncPrefUI();
});
</script>

@stack('scripts')
</body>
</html>