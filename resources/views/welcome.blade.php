<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UM E-Vote | Student Election System</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,700;12..96,800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root { --um-maroon: #8a1538; --um-maroon-dark: #630f28; --um-gold: #fdb813; }
        * { box-sizing: border-box; margin:0; padding:0; }
        body { font-family: 'DM Sans', sans-serif; color: #1e293b; background: white; overflow-x: hidden; }
        html { scroll-behavior: smooth; }
        
        .reveal { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
        
        .hov-card { transition: border-color .3s, box-shadow .3s, transform .3s; }
        .hov-card:hover { border-color: #fbcfe8 !important; box-shadow: 0 15px 35px rgba(138,21,56,.08) !important; transform: translateY(-6px); }
        .hov-nav:hover { color: var(--um-maroon) !important; }
        
        .btn-primary-custom { background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); color: white; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; font-weight: 700; font-size: 14px; padding: 12px 26px; border-radius: 12px; display: inline-flex; align-items: center; gap: 7px; transition: transform 0.3s, box-shadow 0.3s; text-decoration: none; box-shadow: 0 4px 15px rgba(138,21,56,0.25); }
        .btn-primary-custom:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(138,21,56,0.35); color: white; }
        .btn-outline-hov { background: transparent; color: #374151; border: 2px solid #e2e8f0; cursor: pointer; font-family: 'DM Sans', sans-serif; font-weight: 700; font-size: 14px; padding: 12px 26px; border-radius: 12px; display: inline-flex; align-items: center; gap: 7px; transition: all 0.3s; text-decoration: none;}
        .btn-outline-hov:hover { border-color: var(--um-maroon) !important; color: var(--um-maroon) !important; background: #fff0f2; transform: translateY(-3px); }
        
        .sec-hov { transition: all 0.3s; }
        .sec-hov:hover { border-color: #fbcfe8 !important; background: #fff0f2 !important; transform: translateX(8px); }
        
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        .float-anim { animation: float 6s ease-in-out infinite; }
        @media (max-width: 768px) { .hidden-mobile { display: none !important; } }
    </style>
</head>
<body>

    <nav style="position: sticky; top: 0; z-index: 100; background: rgba(255,255,255,0.9); border-bottom: 1px solid #f1f5f9; box-shadow: 0 4px 30px rgba(0,0,0,.03); backdrop-filter: blur(16px);">
        <div style="max-width: 1152px; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; height: 72px;">
            
            <div style="display: flex; align-items: center; gap: 10px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <div style="width: 36px; height: 36px; background: var(--um-maroon); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--um-gold); font-weight: 900; font-family: 'Bricolage Grotesque'; font-size: 18px;">UM</div>
                <span style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 20px; color: #111; letter-spacing: -0.03em;">
                    E-Vote <span style="color: var(--um-maroon);">Portal</span>
                </span>
            </div>

            <div style="display: flex; align-items: center; gap: 36px;" class="hidden-mobile">
                <a href="#hero" class="hov-nav" style="text-decoration: none; font-weight: 600; font-size: 14px; color: #64748b;">Home</a>
                <a href="#features" class="hov-nav" style="text-decoration: none; font-weight: 600; font-size: 14px; color: #64748b;">Features</a>
                <a href="#how-it-works" class="hov-nav" style="text-decoration: none; font-weight: 600; font-size: 14px; color: #64748b;">Process</a>
            </div>

            <div style="display: flex; align-items: center; gap: 16px;" class="hidden-mobile">
                @auth
                    <div style="display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 14px; color: #334155; margin-right: 15px;">
                        <div style="width: 32px; height: 32px; background: #fff0f2; color: var(--um-maroon); border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="bi bi-person-fill"></i></div>
                        <span>{{ auth()->user()->name }}</span>
                    </div>
                    <a href="{{ route('candidates.index') }}" class="btn-primary-custom">Dashboard <i class="bi bi-arrow-right ms-1"></i></a>
                @else
                    <a href="{{ route('login') }}" class="hov-nav" style="text-decoration: none; font-weight: 600; font-size: 15px; color: #475569; padding: 8px 16px;">Student Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <section id="hero" style="background: radial-gradient(circle at top left, #fff0f2 0%, #ffffff 35%, #fff0f2 100%); padding-top: 80px; padding-bottom: 100px; overflow: hidden;">
        <div style="max-width: 1152px; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; gap: 48px; flex-wrap: wrap;">
            
            <div class="reveal" style="flex: 1 1 420px; min-width: 280px;">
                <span style="display: inline-flex; align-items: center; gap: 8px; background: white; color: var(--um-maroon); border: 1px solid #fbcfe8; border-radius: 50px; padding: 6px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(138,21,56,0.05);">
                    <i class="bi bi-mortarboard-fill fs-6 text-warning"></i> Exclusive for UMinians
                </span>
                
                <h1 style="font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; font-size: clamp(36px, 5vw, 56px); line-height: 1.1; letter-spacing: -0.03em; color: #0f172a; margin-bottom: 24px;">
                    The Official<br><span style="color: var(--um-maroon); text-shadow: 0 4px 20px rgba(138,21,56,0.15);">Student Council</span><br>Election Portal
                </h1>
                
                <p style="font-size: 17px; color: #475569; line-height: 1.7; margin-bottom: 40px; max-width: 480px;">
                    Participate in the University of Mindanao's digital democracy. Cast your vote securely for the Supreme Student Government (SSG) and college representatives.
                </p>

                <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 48px;">
                    <a href="{{ route('login') }}" class="btn-primary-custom">Access Dashboard <i class="bi bi-arrow-right"></i></a>
                    <a href="#how-it-works" class="btn-outline-hov">Voting Guidelines <i class="bi bi-chevron-down"></i></a>
                </div>

                <div style="display: flex; align-items: center; gap: 32px; flex-wrap: wrap;">
                    <div><div style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 24px; color: #0f172a;">40K+</div><div style="font-size: 13px; color: #64748b; font-weight: 500;">Active Students</div></div>
                    <div style="width: 2px; height: 40px; background: #e2e8f0; border-radius: 2px;"></div>
                    <div><div style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 24px; color: #0f172a;">15+</div><div style="font-size: 13px; color: #64748b; font-weight: 500;">Colleges</div></div>
                    <div style="width: 2px; height: 40px; background: #e2e8f0; border-radius: 2px;"></div>
                    <div><div style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 24px; color: #0f172a;">100%</div><div style="font-size: 13px; color: #64748b; font-weight: 500;">Verified</div></div>
                </div>
            </div>

            <div class="reveal float-anim" style="flex: 1 1 380px; min-width: 280px; display: flex; justify-content: center; transition-delay: 0.2s;">
                 <div style="position: relative; width: 100%; max-width: 450px; aspect-ratio: 1/1;">
                     <div style="position: absolute; top: 10%; right: 10%; width: 80%; height: 80%; background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); border-radius: 30px; transform: rotate(10deg); box-shadow: 0 20px 50px rgba(138,21,56,0.2);"></div>
                     <div style="position: absolute; top: 15%; right: 15%; width: 80%; height: 80%; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 30px; display: flex; flex-direction: column; gap: 15px;">
                         <div style="width: 40%; height: 20px; background: #f1f5f9; border-radius: 10px;"></div>
                         <div style="width: 100%; height: 60px; background: #fff0f2; border-radius: 12px; border-left: 4px solid var(--um-maroon); display: flex; align-items: center; padding: 0 15px;"><div style="width: 30px; height: 30px; border-radius: 50%; background: var(--um-maroon);"></div><div style="margin-left: 15px; width: 50%; height: 10px; background: #fecdd3; border-radius: 5px;"></div></div>
                         <div style="width: 100%; height: 60px; background: #f8fafc; border-radius: 12px; border-left: 4px solid #cbd5e1; display: flex; align-items: center; padding: 0 15px;"><div style="width: 30px; height: 30px; border-radius: 50%; background: #94a3b8;"></div><div style="margin-left: 15px; width: 60%; height: 10px; background: #e2e8f0; border-radius: 5px;"></div></div>
                         <div style="width: 100%; height: 60px; background: #f8fafc; border-radius: 12px; border-left: 4px solid #cbd5e1; display: flex; align-items: center; padding: 0 15px;"><div style="width: 30px; height: 30px; border-radius: 50%; background: #94a3b8;"></div><div style="margin-left: 15px; width: 40%; height: 10px; background: #e2e8f0; border-radius: 5px;"></div></div>
                         <div style="margin-top: auto; width: 100%; height: 40px; background: var(--um-gold); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">SUBMIT BALLOT</div>
                     </div>
                     <div style="position: absolute; bottom: 5%; left: 0; width: 120px; height: 120px; background: var(--um-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(253,184,19,0.3); font-size: 40px; color: white;"><i class="bi bi-check-lg"></i></div>
                 </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" style="background: linear-gradient(180deg, #f8fafc 0%, #fff0f2 100%); padding: 100px 0; border-top: 1px solid rgba(226, 232, 240, 0.6);">
        <div style="max-width: 1152px; margin: 0 auto; padding: 0 24px;">
            <div class="reveal" style="text-align: center; max-width: 560px; margin: 0 auto 72px;">
                <span style="background: white; color: var(--um-maroon); padding: 6px 16px; border-radius: 50px; font-weight: 800; font-size: 12px; letter-spacing: 0.15em; text-transform: uppercase; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">Voting Process</span>
                <h2 style="font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; font-size: clamp(28px, 4vw, 44px); color: #0f172a; margin-top: 16px; margin-bottom: 16px;">How to Cast Your Vote</h2>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 32px;">
                <div class="reveal" style="text-align: center;">
                    <div style="position: relative; display: inline-flex; margin-bottom: 24px;">
                        <div style="width: 88px; height: 88px; border-radius: 50%; background: white; border: 2px solid rgba(138,21,56, 0.3); display: flex; align-items: center; justify-content: center; font-size: 32px; color: var(--um-maroon); box-shadow: 0 10px 25px rgba(138,21,56,0.1);"><i class="bi bi-person-vcard"></i></div>
                        <span style="position: absolute; top: -4px; right: -4px; width: 28px; height: 28px; border-radius: 50%; background: var(--um-gold); color: white; font-size: 13px; font-weight: 800; display: flex; align-items: center; justify-content: center; font-family: 'Bricolage Grotesque'; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">1</span>
                    </div>
                    <h3 style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 18px; color: #0f172a; margin-bottom: 12px;">Access Portal</h3>
                    <p style="color: #475569; font-size: 15px; line-height: 1.7; max-width: 240px; margin: 0 auto;">Log in using your registered UM student credentials.</p>
                </div>
                <div class="reveal" style="text-align: center; transition-delay: 0.1s;">
                    <div style="position: relative; display: inline-flex; margin-bottom: 24px;">
                        <div style="width: 88px; height: 88px; border-radius: 50%; background: white; border: 2px solid rgba(138,21,56, 0.3); display: flex; align-items: center; justify-content: center; font-size: 32px; color: var(--um-maroon); box-shadow: 0 10px 25px rgba(138,21,56,0.1);"><i class="bi bi-card-checklist"></i></div>
                        <span style="position: absolute; top: -4px; right: -4px; width: 28px; height: 28px; border-radius: 50%; background: var(--um-gold); color: white; font-size: 13px; font-weight: 800; display: flex; align-items: center; justify-content: center; font-family: 'Bricolage Grotesque'; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">2</span>
                    </div>
                    <h3 style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 18px; color: #0f172a; margin-bottom: 12px;">Review Candidates</h3>
                    <p style="color: #475569; font-size: 15px; line-height: 1.7; max-width: 240px; margin: 0 auto;">Browse the platforms of SSG candidates and your college representatives.</p>
                </div>
                <div class="reveal" style="text-align: center; transition-delay: 0.2s;">
                    <div style="position: relative; display: inline-flex; margin-bottom: 24px;">
                        <div style="width: 88px; height: 88px; border-radius: 50%; background: white; border: 2px solid rgba(138,21,56, 0.3); display: flex; align-items: center; justify-content: center; font-size: 32px; color: var(--um-maroon); box-shadow: 0 10px 25px rgba(138,21,56,0.1);"><i class="bi bi-ui-checks-grid"></i></div>
                        <span style="position: absolute; top: -4px; right: -4px; width: 28px; height: 28px; border-radius: 50%; background: var(--um-gold); color: white; font-size: 13px; font-weight: 800; display: flex; align-items: center; justify-content: center; font-family: 'Bricolage Grotesque'; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">3</span>
                    </div>
                    <h3 style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 18px; color: #0f172a; margin-bottom: 12px;">Cast Your Vote</h3>
                    <p style="color: #475569; font-size: 15px; line-height: 1.7; max-width: 240px; margin: 0 auto;">Make your selections and submit. Ensure your choices are final.</p>
                </div>
            </div>
            
            <div class="reveal" style="text-align: center; margin-top: 64px;">
                <a href="{{ route('login') }}" class="btn-primary-custom" style="padding: 16px 36px; font-size: 16px;">Go to Login <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
        </div>
    </section>

    <footer id="contact" style="background: #0f172a; color: #94a3b8; padding: 80px 0 32px;">
        <div class="reveal" style="max-width: 1152px; margin: 0 auto; padding: 0 24px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 48px; margin-bottom: 64px;">
                
                <div style="display: flex; flex-direction: column; justify-content: flex-end;">
                    <div style="display: flex; align-items: flex-end; margin-bottom: 16px;">
                        <div style="width: 32px; height: 32px; background: var(--um-maroon); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--um-gold); font-weight: 900; font-family: 'Bricolage Grotesque'; font-size: 18px; margin-right: 10px;">UM</div>
                        <span style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 24px; color: white; line-height: 1;">E-Vote <span style="color: var(--um-maroon);">Portal</span></span>
                    </div>
                    <p style="font-size: 15px; line-height: 1.6; margin: 0; max-width: 260px;">The official secure student election system for the University of Mindanao.</p>
                </div>

                <div>
                    <h4 style="font-family: 'Bricolage Grotesque'; font-weight: 800; font-size: 16px; color: white; margin-bottom: 24px; letter-spacing: 0.02em;">Links</h4>
                    <ul style="list-style: none;">
                        <li style="margin-bottom: 14px;"><a href="#hero" class="footer-link" style="color: #94a3b8; text-decoration: none; font-size: 15px; font-weight: 500;">Home</a></li>
                        <li style="margin-bottom: 14px;"><a href="#how-it-works" class="footer-link" style="color: #94a3b8; text-decoration: none; font-size: 15px; font-weight: 500;">Voting Process</a></li>
                    </ul>
                </div>
            </div>

            <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 32px; display: flex; flex-wrap: wrap; gap: 16px; justify-content: space-between; align-items: center;">
                <p style="font-size: 14px; margin: 0;">© 2026 University of Mindanao. All rights reserved.</p>
                <div style="display: flex; gap: 32px;">
                    <a href="#" class="footer-link" style="color: #94a3b8; text-decoration: none; font-size: 14px;">Privacy Policy</a>
                    <a href="#" class="footer-link" style="color: #94a3b8; text-decoration: none; font-size: 14px;">Contact Admin</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const reveals = document.querySelectorAll(".reveal");
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add("active"); });
            }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });
            reveals.forEach(reveal => observer.observe(reveal));
        });
    </script>
</body>
</html>