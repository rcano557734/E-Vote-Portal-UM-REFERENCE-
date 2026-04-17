<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UM Student Election System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,700;12..96,800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Official UM Colors */
            --um-maroon: #8a1538;
            --um-maroon-dark: #5c0d24;
            --um-maroon-light: #fff0f2;
            --um-gold: #fdb813;
            --um-gold-dark: #d97706;
            
            /* Gray Dashboard Theme */
            --bg-gray: #e2e8f0;       /* Distinct slate gray background */
            --sidebar-bg: #ffffff;    /* Crisp white sidebar */
            --text-dark: #0f172a;
            --text-muted: #475569;
        }

        body { font-family: 'DM Sans', sans-serif; background-color: var(--bg-gray); color: var(--text-dark); margin: 0; padding: 0; overflow-x: hidden;}
        
        /* Layout Grid */
        .app-wrapper { display: flex; min-height: 100vh; }
        
        /* Sidebar Styling */
        .sidebar { width: 280px; background: var(--sidebar-bg); border-right: 1px solid #cbd5e1; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 100; box-shadow: 4px 0 15px rgba(0,0,0,0.03);}
        .sidebar-brand { padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 12px; text-decoration: none;}
        .sidebar-menu { padding: 24px 16px; flex: 1; overflow-y: auto;}
        
        /* Navigation Items */
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 14px 18px; color: var(--text-muted); text-decoration: none; font-weight: 600; font-size: 15px; border-radius: 12px; transition: all 0.2s; margin-bottom: 8px;}
        .nav-item:hover { background: #f1f5f9; color: var(--text-dark); }
        .nav-item.active { background: var(--um-maroon-light); color: var(--um-maroon); border-left: 4px solid var(--um-maroon); }
        .nav-item i { font-size: 18px; }
        
        /* Sidebar Footer (Profile & Logout) */
        .sidebar-footer { padding: 20px 16px; border-top: 1px solid #f1f5f9; background: #f8fafc;}
        .logout-btn { width: 100%; background: transparent; border: 1.5px solid #fecaca; text-align: left; display: flex; align-items: center; justify-content: center; gap: 10px; padding: 12px 16px; color: #ef4444; font-weight: 700; font-size: 14px; border-radius: 12px; cursor: pointer; transition: all 0.2s;}
        .logout-btn:hover { background: #fef2f2; border-color: #fca5a5; transform: translateY(-2px);}

        /* Main Content Area */
        .main-content { flex: 1; margin-left: 280px; display: flex; flex-direction: column; min-height: 100vh; }
        .top-header { background: rgba(226, 232, 240, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid #cbd5e1; padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 50;}
        .content-area { padding: 40px; flex: 1; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-gray); }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
    </style>
</head>
<body>
    
    <div class="app-wrapper">
        <aside class="sidebar">
            <a href="/" class="sidebar-brand">
                <div style="width: 36px; height: 36px; background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--um-gold); font-weight: 900; font-family: 'Bricolage Grotesque'; font-size: 18px; box-shadow: 0 4px 10px rgba(138,21,56,0.3);">UM</div>
                <span style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 19px; color: var(--text-dark); letter-spacing: -0.02em;">E-Vote <span style="color: var(--um-maroon);">Portal</span></span>
            </a>
            
            <nav class="sidebar-menu">
                <div style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.12em; margin: 10px 0 16px 18px;">Menu</div>
                
                <a href="{{ route('candidates.index') }}" class="nav-item {{ request()->routeIs('candidates.index') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
                
                @if(auth()->user()->role_id === 3)
                    <a href="{{ route('history') }}" class="nav-item {{ request()->routeIs('history') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Election History
                    </a>
                @else
                    <a href="{{ route('ledger') }}" class="nav-item {{ request()->routeIs('ledger') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i> Audit Ledger
                    </a>
                @endif
                
                <div style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.12em; margin: 36px 0 16px 18px;">Preferences</div>
                
                <a href="#" class="nav-item">
                    <i class="bi bi-person-badge"></i> Student Profile
                </a>
                <a href="#" class="nav-item">
                    <i class="bi bi-universal-access-circle"></i> Accessibility
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="d-flex align-items-center gap-3 mb-4 px-2">
                    <div style="width: 42px; height: 42px; background: var(--um-maroon-light); color: var(--um-maroon); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px;"><i class="bi bi-person-fill"></i></div>
                    <div style="line-height: 1.3;">
                        <div class="fw-bold" style="font-size: 14px; color: var(--text-dark);">{{ auth()->user()->name ?? 'UM Student' }}</div>
                        <div style="font-size: 11px; color: var(--um-maroon); font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">{{ auth()->user()->role->role_name ?? 'Voter' }}</div>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right"></i> Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <div>
                    <h4 class="m-0" style="font-family: 'Bricolage Grotesque'; font-weight: 800; color: var(--text-dark);">Welcome, {{ auth()->user()->name ?? 'Student' }}</h4>
                    <span style="font-size: 13px; color: var(--text-muted); font-weight: 500;">University of Mindanao • {{ now()->format('l, F j, Y') }}</span>
                </div>
                <div class="d-flex gap-3">
                    <button class="btn border-0 shadow-sm rounded-circle" style="width: 44px; height: 44px; background: white; color: var(--um-maroon); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="bi bi-bell-fill"></i>
                    </button>
                </div>
            </header>

            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>