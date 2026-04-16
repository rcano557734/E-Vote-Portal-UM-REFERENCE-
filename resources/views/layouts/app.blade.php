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
            --um-maroon: #8a1538;
            --um-maroon-dark: #630f28;
            --um-maroon-light: #fdf2f5;
            --um-gold: #fdb813;
        }
        body { font-family: 'DM Sans', sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 0; overflow-x: hidden;}
        
        .app-wrapper { display: flex; min-height: 100vh; }
        
        /* Sidebar Styling */
        .sidebar { width: 280px; background: white; border-right: 1px solid #f1f5f9; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 100;}
        .sidebar-brand { padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px; text-decoration: none;}
        .sidebar-menu { padding: 20px 16px; flex: 1; overflow-y: auto;}
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #64748b; text-decoration: none; font-weight: 600; font-size: 15px; border-radius: 12px; transition: all 0.2s; margin-bottom: 8px;}
        .nav-item:hover, .nav-item.active { background: var(--um-maroon-light); color: var(--um-maroon); }
        .nav-item i { font-size: 18px; }
        
        /* Sidebar Bottom */
        .sidebar-footer { padding: 20px 16px; border-top: 1px solid #f1f5f9; }
        .logout-btn { width: 100%; background: transparent; border: none; text-align: left; display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #ef4444; font-weight: 600; font-size: 15px; border-radius: 12px; cursor: pointer; transition: all 0.2s;}
        .logout-btn:hover { background: #fef2f2; }

        /* Main Content Area */
        .main-content { flex: 1; margin-left: 280px; display: flex; flex-direction: column; min-height: 100vh; }
        .top-header { background: rgba(255,255,255,0.8); backdrop-filter: blur(16px); border-bottom: 1px solid #f1f5f9; padding: 16px 40px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 50;}
        .content-area { padding: 40px; flex: 1; }
    </style>
</head>
<body>
    
    <div class="app-wrapper">
        <aside class="sidebar">
            <a href="/" class="sidebar-brand">
                <div style="width: 32px; height: 32px; background: var(--um-maroon); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--um-gold); font-weight: 900; font-family: 'Bricolage Grotesque'; font-size: 18px;">UM</div>
                <span style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 18px; color: #111;">E-Vote <span style="color: var(--um-maroon);">Portal</span></span>
            </a>
            
            <nav class="sidebar-menu">
                <div style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin: 10px 0 16px 16px;">Menu</div>
                
                <a href="{{ route('candidates.index') }}" class="nav-item active">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
                <a href="#" class="nav-item">
                    <i class="bi bi-clock-history"></i> Election History
                </a>
                
                <div style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin: 30px 0 16px 16px;">Preferences</div>
                
                <a href="#" class="nav-item">
                    <i class="bi bi-person-badge"></i> Student Profile
                </a>
                <a href="#" class="nav-item">
                    <i class="bi bi-universal-access-circle"></i> Accessibility
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="d-flex align-items-center gap-3 mb-3 px-3">
                    <div style="width: 36px; height: 36px; background: var(--um-maroon-light); color: var(--um-maroon); border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="bi bi-person-fill"></i></div>
                    <div style="line-height: 1.2;">
                        <div class="fw-bold" style="font-size: 14px; color: #0f172a;">{{ auth()->user()->name ?? 'UM Student' }}</div>
                        <div style="font-size: 11px; color: var(--um-maroon); font-weight: 700; text-transform: uppercase;">{{ auth()->user()->role->role_name ?? 'Voter' }}</div>
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
                    <h4 class="m-0" style="font-family: 'Bricolage Grotesque'; font-weight: 700;">Welcome, {{ auth()->user()->name ?? 'Student' }}</h4>
                    <span class="text-muted" style="font-size: 13px;">University of Mindanao • {{ now()->format('l, F j, Y') }}</span>
                </div>
                <div class="d-flex gap-3">
                    <button class="btn btn-light border rounded-circle" style="width: 40px; height: 40px; color: var(--um-maroon);"><i class="bi bi-bell-fill"></i></button>
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