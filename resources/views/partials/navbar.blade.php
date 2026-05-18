<nav style="position: sticky; top: 0; z-index: 100; background: rgba(255, 255, 255, 0.8); border-bottom: 1px solid rgba(22, 163, 74, 0.15); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);">
    <div style="max-width: 1152px; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; height: 72px;">
        
        <a href="/" style="display: flex; align-items: center; gap: 10px; text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
            <svg width="36" height="36" viewBox="0 0 34 34" fill="none">
                <rect width="34" height="34" rx="10" fill="url(#grad1)"/>
                <defs>
                    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#22c55e;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#15803d;stop-opacity:1" />
                    </linearGradient>
                </defs>
                <path d="M9 17.5L14.5 23L25 12" stroke="white" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="17" cy="17" r="12" stroke="white" stroke-width="1.5" fill="none" opacity="0.3"/>
            </svg>
            <span style="font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; font-size: 20px; color: #0f172a; letter-spacing: -0.03em;">
                Vote<span style="color: #16a34a;">System</span>
            </span>
        </a>

        <div style="display: flex; align-items: center; gap: 24px;">
            @auth
                <div style="display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 14px; color: #334155;">
                    <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; box-shadow: 0 2px 10px rgba(22, 163, 74, 0.2);">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div style="display: flex; flex-direction: column; line-height: 1.2;">
                        <span>{{ auth()->user()->name }}</span>
                        <span style="font-size: 11px; color: #16a34a; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">{{ auth()->user()->role->role_name }}</span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="m-0 confirm-form" 
                    data-title="Ready to leave?" 
                    data-text="Are you sure you want to log out of your session?" 
                    data-btn="Yes, Log Out" 
                    data-color="#dc2626">
                    @csrf
                    <button type="submit" style="background: rgba(254, 226, 226, 0.5); border: 1.5px solid #fecaca; color: #dc2626; padding: 8px 18px; border-radius: 10px; font-weight: 700; font-size: 13px; transition: all 0.2s; cursor: pointer;" 
                        onmouseover="this.style.background='#fee2e2'; this.style.transform='translateY(-2px)';" 
                        onmouseout="this.style.background='rgba(254, 226, 226, 0.5)'; this.style.transform='translateY(0)';">
                        Log Out <i class="bi bi-box-arrow-right ms-1"></i>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" style="text-decoration: none; font-weight: 600; font-size: 15px; color: #475569; padding: 8px 16px; transition: color 0.2s;" onmouseover="this.style.color='#16a34a'" onmouseout="this.style.color='#475569'">Login</a>
                <a href="{{ route('register') }}" style="background: linear-gradient(135deg, #22c55e, #16a34a); color: white; border: none; font-weight: 600; font-size: 15px; padding: 10px 24px; border-radius: 10px; text-decoration: none; box-shadow: 0 4px 15px rgba(22, 163, 74, 0.3); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(22, 163, 74, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(22, 163, 74, 0.3)';">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>