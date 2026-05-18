@extends('layouts.app')
@section('title', 'Profile & Settings')

@push('styles')
<style>
    /* ── Page titles ─────────────────────────────────────────────── */
    .dash-title { font-family:'Bricolage Grotesque',sans-serif; font-weight:800; color:var(--text-heading); letter-spacing:-0.03em; }

    /* ── Cards ───────────────────────────────────────────────────── */
    .profile-card { background:var(--bg-surface); border-radius:20px; border:1px solid var(--border-col); box-shadow:var(--shadow-md); overflow:hidden; transition:background .3s,border-color .3s; }
    .card-section-header {
        padding:20px 26px; border-bottom:1px solid var(--border-col);
        display:flex; align-items:center; gap:12px;
        background:var(--bg-surface-alt); transition:background .3s,border-color .3s;
    }
    .section-icon {
        width:38px; height:38px; border-radius:10px;
        background:var(--um-maroon-light); color:var(--um-maroon);
        display:flex; align-items:center; justify-content:center; font-size:17px; flex-shrink:0;
    }

    /* ── Form inputs ─────────────────────────────────────────────── */
    .input-um {
        width:100%; padding:13px 15px; border-radius:12px;
        border:2px solid var(--border-col); font-family:'DM Sans',sans-serif;
        font-size:var(--fs-base); color:var(--text-heading);
        background:var(--input-bg); transition:all .2s; outline:none;
    }
    .input-um:focus    { border-color:var(--um-maroon); background:var(--bg-surface); box-shadow:0 0 0 4px rgba(138,21,56,.1); }
    .input-um.is-error { border-color:#ef4444 !important; background:#fef2f2 !important; }
    .label-um { font-weight:700; font-size:var(--fs-xs); text-transform:uppercase; letter-spacing:.08em; color:var(--text-muted); margin-bottom:6px; display:block; }

    /* ── Save button ─────────────────────────────────────────────── */
    .btn-save {
        background:linear-gradient(135deg,var(--um-maroon),var(--um-maroon-dark));
        color:white; padding:13px 28px; border-radius:12px;
        font-weight:700; font-size:var(--fs-base); border:none;
        box-shadow:0 4px 15px rgba(138,21,56,.25);
        transition:all .2s; text-transform:uppercase; letter-spacing:.05em; cursor:pointer;
    }
    .btn-save:hover { transform:translateY(-2px); box-shadow:0 8px 25px rgba(138,21,56,.35); }

    /* ── Stat pills ──────────────────────────────────────────────── */
    .stat-pill { background:var(--bg-surface-alt); border:1px solid var(--border-col); border-radius:14px; padding:16px 18px; text-align:center; transition:background .3s,border-color .3s; }

    /* ════════════════════════════════════════════════════════════════
       ACCESSIBILITY PREFERENCE PANEL
    ════════════════════════════════════════════════════════════════ */
    .pref-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:16px; }

    .pref-group { background:var(--bg-surface-alt); border:1px solid var(--border-col); border-radius:16px; padding:20px; transition:background .3s,border-color .3s; }
    .pref-group-title { font-size:var(--fs-xs); font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--text-faint); margin-bottom:14px; display:flex; align-items:center; gap:7px; }

    /* Toggle switch */
    .toggle-wrap { display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .toggle-label { font-size:var(--fs-sm); font-weight:600; color:var(--text-heading); }
    .toggle-desc  { font-size:11px; color:var(--text-muted); margin-top:2px; }

    .um-toggle { position:relative; width:46px; height:25px; flex-shrink:0; }
    .um-toggle input { opacity:0; width:0; height:0; position:absolute; }
    .um-toggle-track {
        position:absolute; inset:0; background:#e2e8f0; border-radius:25px;
        cursor:pointer; transition:background .3s;
    }
    .um-toggle input:checked ~ .um-toggle-track { background:var(--um-maroon); }
    .um-toggle-thumb {
        position:absolute; top:3px; left:3px; width:19px; height:19px;
        background:white; border-radius:50%; transition:transform .3s; pointer-events:none;
        box-shadow:0 1px 4px rgba(0,0,0,0.2);
    }
    .um-toggle input:checked ~ .um-toggle-track .um-toggle-thumb { transform:translateX(21px); }

    /* Button group selectors */
    .pref-btn-group { display:flex; gap:6px; flex-wrap:wrap; }
    .pref-btn {
        padding:7px 14px; border-radius:8px; font-size:var(--fs-sm); font-weight:700;
        border:2px solid var(--border-col); background:var(--bg-surface);
        color:var(--text-muted); cursor:pointer; transition:all .2s; font-family:'DM Sans',sans-serif;
    }
    .pref-btn:hover  { border-color:var(--um-maroon); color:var(--um-maroon); }
    .pref-btn.pref-active,
    .pref-btn[aria-pressed="true"] {
        background:var(--um-maroon); color:white; border-color:var(--um-maroon);
        box-shadow:0 2px 8px rgba(138,21,56,.25);
    }

    /* Color swatch buttons for theme */
    .theme-swatch {
        width:36px; height:36px; border-radius:10px; border:3px solid transparent;
        cursor:pointer; transition:all .2s; flex-shrink:0;
    }
    .theme-swatch.pref-active,
    .theme-swatch[aria-pressed="true"] { border-color:var(--um-maroon); box-shadow:0 0 0 2px white,0 0 0 4px var(--um-maroon); }
    .swatch-light  { background:linear-gradient(135deg,#f1f5f9,#fff); border:2px solid #e2e8f0; }
    .swatch-dark   { background:linear-gradient(135deg,#0f172a,#1e293b); }
    .swatch-system { background:linear-gradient(135deg,#fff 50%,#1e293b 50%); border:2px solid #e2e8f0; }

    /* Slider for font preview */
    .font-preview-text { font-family:'Bricolage Grotesque',sans-serif; font-weight:800; color:var(--um-maroon); line-height:1; margin-top:10px; transition:font-size .2s; text-align:center; }

    /* Responsive profile adjustments */
    @media (max-width: 600px) {
        .pref-grid { grid-template-columns: 1fr; }
        .profile-header-inner { flex-direction:column; text-align:center; }
        .profile-header-inner .role-badge-wrap { justify-content:center; }
    }
</style>
@endpush

@section('content')

<div style="max-width:860px;margin:0 auto;">

    {{-- Page Heading --}}
    <div class="mb-4">
        <h2 class="dash-title m-0" style="font-size:var(--fs-4xl);">Profile &amp; Settings</h2>
        <p style="color:var(--text-muted);margin-top:6px;font-size:var(--fs-sm);">Manage your account, security, and display preferences.</p>
    </div>

    {{-- ── Profile Header Card ──────────────────────────────────────── --}}
    <div class="profile-card mb-4">
        <div style="background:linear-gradient(135deg,var(--um-maroon),var(--um-maroon-dark));padding:28px 28px;">
            <div class="d-flex align-items-center gap-4 profile-header-inner">
                <div style="width:70px;height:70px;background:rgba(255,255,255,0.15);border:3px solid rgba(255,255,255,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:30px;color:white;flex-shrink:0;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <h3 class="m-0" style="font-family:'Bricolage Grotesque';font-weight:800;color:white;font-size:var(--fs-2xl);">{{ $user->name }}</h3>
                    <div style="color:rgba(255,255,255,0.75);font-size:var(--fs-sm);margin-top:3px;">{{ $user->email }}</div>
                    @php
                        $roleLabel = match($user->role_id){ 1=>'System Administrator', 2=>'Electoral Auditor', default=>'Registered Voter' };
                        $roleIcon  = match($user->role_id){ 1=>'bi-shield-fill-check', 2=>'bi-eye-fill', default=>'bi-person-check-fill' };
                    @endphp
                    <span class="role-badge-wrap" style="display:flex;margin-top:10px;">
                        <span style="display:inline-flex;align-items:center;gap:6px;background:rgba(253,184,19,0.2);color:var(--um-gold);border:1px solid rgba(253,184,19,0.4);padding:4px 13px;border-radius:20px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.05em;">
                            <i class="bi {{ $roleIcon }}"></i> {{ $roleLabel }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div style="padding:16px 20px;background:var(--bg-surface-alt);border-bottom:1px solid var(--border-col);">
            <div class="row g-3">
                <div class="col-4">
                    <div class="stat-pill">
                        <div style="font-size:10px;font-weight:800;color:var(--text-faint);text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px;">Account ID</div>
                        <div style="font-family:'Bricolage Grotesque';font-weight:800;font-size:var(--fs-2xl);color:var(--text-heading);">#{{ str_pad($user->id,4,'0',STR_PAD_LEFT) }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-pill">
                        <div style="font-size:10px;font-weight:800;color:var(--text-faint);text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px;">
                            @if($user->role_id===3) Ballots Cast @else Log Entries @endif
                        </div>
                        <div style="font-family:'Bricolage Grotesque';font-weight:800;font-size:var(--fs-2xl);color:var(--um-maroon);">{{ $voteCount }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-pill">
                        <div style="font-size:10px;font-weight:800;color:var(--text-faint);text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px;">Member Since</div>
                        <div style="font-family:'Bricolage Grotesque';font-weight:800;font-size:var(--fs-lg);color:var(--text-heading);">{{ $user->created_at->format('M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         ACCESSIBILITY & DISPLAY PREFERENCES
    ══════════════════════════════════════════════════════════════ --}}
    <div class="profile-card mb-4">
        <div class="card-section-header">
            <div class="section-icon"><i class="bi bi-sliders"></i></div>
            <div>
                <h5 class="m-0 dash-title" style="font-size:var(--fs-lg);">Display &amp; Accessibility</h5>
                <div style="font-size:11px;color:var(--text-muted);margin-top:1px;">Preferences are saved to your browser and persist after logout.</div>
            </div>
        </div>

        <div style="padding:24px;">
            <div class="pref-grid">

                {{-- 1. COLOR THEME --}}
                <div class="pref-group">
                    <div class="pref-group-title"><i class="bi bi-palette-fill"></i> Color Theme</div>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <button type="button" class="theme-swatch swatch-light"
                                data-pref-key="theme" data-pref-val="light"
                                onclick="setTheme('light')" title="Light Mode" aria-label="Light theme"></button>
                        <button type="button" class="theme-swatch swatch-dark"
                                data-pref-key="theme" data-pref-val="dark"
                                onclick="setTheme('dark')" title="Dark Mode" aria-label="Dark theme"></button>
                        <button type="button" class="theme-swatch swatch-system"
                                data-pref-key="theme" data-pref-val="system"
                                onclick="applySystemTheme()" title="Follow System" aria-label="System theme"></button>
                    </div>
                    <div style="margin-top:12px;">
                        <label class="toggle-wrap">
                            <div>
                                <div class="toggle-label">Dark Mode</div>
                                <div class="toggle-desc">Easier on the eyes in low light</div>
                            </div>
                            <label class="um-toggle">
                                <input type="checkbox" id="darkToggle" onchange="setTheme(this.checked?'dark':'light')">
                                <div class="um-toggle-track"><div class="um-toggle-thumb"></div></div>
                            </label>
                        </label>
                    </div>
                </div>

                {{-- 2. FONT SIZE --}}
                <div class="pref-group">
                    <div class="pref-group-title"><i class="bi bi-fonts"></i> Font Size</div>
                    <div class="pref-btn-group">
                        <button type="button" class="pref-btn" data-pref-key="fontSize" data-pref-val="sm"  onclick="setFontSize('sm')">Small</button>
                        <button type="button" class="pref-btn" data-pref-key="fontSize" data-pref-val="md"  onclick="setFontSize('md')">Default</button>
                        <button type="button" class="pref-btn" data-pref-key="fontSize" data-pref-val="lg"  onclick="setFontSize('lg')">Large</button>
                        <button type="button" class="pref-btn" data-pref-key="fontSize" data-pref-val="xl"  onclick="setFontSize('xl')">X-Large</button>
                    </div>
                    <div class="font-preview-text" id="fontPreview">Aa</div>
                    <div style="font-size:11px;color:var(--text-muted);text-align:center;margin-top:4px;">Preview text above</div>
                </div>

                {{-- 3. HIGH CONTRAST --}}
                <div class="pref-group">
                    <div class="pref-group-title"><i class="bi bi-circle-half"></i> Contrast</div>
                    <label class="toggle-wrap">
                        <div>
                            <div class="toggle-label">High Contrast</div>
                            <div class="toggle-desc">Stronger borders &amp; darker text</div>
                        </div>
                        <label class="um-toggle">
                            <input type="checkbox" id="contrastToggle" onchange="setContrast(this.checked?'high':'normal')">
                            <div class="um-toggle-track"><div class="um-toggle-thumb"></div></div>
                        </label>
                    </label>
                </div>

                {{-- 4. REDUCE MOTION --}}
                <div class="pref-group">
                    <div class="pref-group-title"><i class="bi bi-wind"></i> Motion</div>
                    <label class="toggle-wrap">
                        <div>
                            <div class="toggle-label">Reduce Motion</div>
                            <div class="toggle-desc">Disables animations &amp; transitions</div>
                        </div>
                        <label class="um-toggle">
                            <input type="checkbox" id="motionToggle" onchange="setMotion(this.checked?'reduce':'normal')">
                            <div class="um-toggle-track"><div class="um-toggle-thumb"></div></div>
                        </label>
                    </label>
                </div>

                {{-- 5. DYSLEXIA-FRIENDLY FONT --}}
                <div class="pref-group">
                    <div class="pref-group-title"><i class="bi bi-type"></i> Reading Aid</div>
                    <label class="toggle-wrap">
                        <div>
                            <div class="toggle-label">Dyslexia-Friendly Font</div>
                            <div class="toggle-desc">Switches to OpenDyslexic font</div>
                        </div>
                        <label class="um-toggle">
                            <input type="checkbox" id="dyslexiaToggle" onchange="setDyslexia(this.checked)">
                            <div class="um-toggle-track"><div class="um-toggle-thumb"></div></div>
                        </label>
                    </label>
                </div>

                {{-- 6. COMPACT MODE --}}
                <div class="pref-group">
                    <div class="pref-group-title"><i class="bi bi-layout-sidebar-inset"></i> Layout</div>
                    <label class="toggle-wrap">
                        <div>
                            <div class="toggle-label">Compact Mode</div>
                            <div class="toggle-desc">Tighter spacing for more content</div>
                        </div>
                        <label class="um-toggle">
                            <input type="checkbox" id="compactToggle" onchange="setCompact(this.checked)">
                            <div class="um-toggle-track"><div class="um-toggle-thumb"></div></div>
                        </label>
                    </label>
                </div>

            </div>

            {{-- Reset all --}}
            <div class="mt-4 pt-3" style="border-top:1px solid var(--border-col);">
                <button type="button" onclick="resetPrefs()"
                        style="background:none;border:1.5px solid var(--border-col);color:var(--text-muted);padding:8px 18px;border-radius:9px;font-size:var(--fs-sm);font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;"
                        onmouseover="this.style.borderColor='#ef4444';this.style.color='#ef4444'"
                        onmouseout="this.style.borderColor='var(--border-col)';this.style.color='var(--text-muted)'">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset All to Default
                </button>
            </div>
        </div>
    </div>

    {{-- ── Account Information Form ─────────────────────────────────── --}}
    <div class="profile-card mb-4">
        <div class="card-section-header">
            <div class="section-icon"><i class="bi bi-pencil-square"></i></div>
            <div>
                <h5 class="m-0 dash-title" style="font-size:var(--fs-lg);">Account Information</h5>
                <div style="font-size:11px;color:var(--text-muted);margin-top:1px;">Update your name and email address.</div>
            </div>
        </div>

        @if($errors->any())
            <div class="mx-4 mt-4" style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:14px 18px;color:#991b1b;font-size:var(--fs-sm);">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Please fix the errors below.</strong>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="p-4 p-md-5 pt-4" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="label-um" for="name">Full Name <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="name" name="name"
                           class="input-um @error('name') is-error @enderror"
                           value="{{ old('name',$user->name) }}" placeholder="Your full name" required>
                    @error('name') <div style="color:#ef4444;font-size:12px;margin-top:5px;font-weight:600;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="label-um" for="email">Email Address <span style="color:#ef4444;">*</span></label>
                    <input type="email" id="email" name="email"
                           class="input-um @error('email') is-error @enderror"
                           value="{{ old('email',$user->email) }}" placeholder="Your email address" required>
                    @error('email') <div style="color:#ef4444;font-size:12px;margin-top:5px;font-weight:600;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>
            </div>

            <hr style="border-color:var(--border-col);margin:0 0 24px;">

            <div class="mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-lock-fill" style="color:var(--um-maroon);"></i>
                <span class="dash-title" style="font-size:var(--fs-base);">Change Password</span>
                <span style="font-size:11px;color:var(--text-muted);">(Leave blank to keep current)</span>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <label class="label-um">Current Password</label>
                    <div style="position:relative;">
                        <input type="password" id="cur_pwd" name="current_password"
                               class="input-um @error('current_password') is-error @enderror"
                               placeholder="Current password" style="padding-right:44px;">
                        <button type="button" onclick="togglePwd('cur_pwd',this)"
                                style="position:absolute;right:13px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-faint);cursor:pointer;padding:0;">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                    @error('current_password') <div style="color:#ef4444;font-size:12px;margin-top:5px;font-weight:600;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="label-um">New Password</label>
                    <div style="position:relative;">
                        <input type="password" id="new_pwd" name="new_password"
                               class="input-um @error('new_password') is-error @enderror"
                               placeholder="Min. 8 characters" style="padding-right:44px;"
                               oninput="checkStrength(this.value)">
                        <button type="button" onclick="togglePwd('new_pwd',this)"
                                style="position:absolute;right:13px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-faint);cursor:pointer;padding:0;">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                    <div class="d-flex gap-1 mt-2" id="strengthBar">
                        <span style="flex:1;height:3px;border-radius:2px;background:var(--border-col);transition:background .3s;" id="s1"></span>
                        <span style="flex:1;height:3px;border-radius:2px;background:var(--border-col);transition:background .3s;" id="s2"></span>
                        <span style="flex:1;height:3px;border-radius:2px;background:var(--border-col);transition:background .3s;" id="s3"></span>
                        <span style="flex:1;height:3px;border-radius:2px;background:var(--border-col);transition:background .3s;" id="s4"></span>
                    </div>
                    @error('new_password') <div style="color:#ef4444;font-size:12px;margin-top:5px;font-weight:600;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="label-um">Confirm New Password</label>
                    <div style="position:relative;">
                        <input type="password" id="conf_pwd" name="new_password_confirmation"
                               class="input-um" placeholder="Re-enter password" style="padding-right:44px;">
                        <button type="button" onclick="togglePwd('conf_pwd',this)"
                                style="position:absolute;right:13px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-faint);cursor:pointer;padding:0;">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 flex-wrap">
                <button type="submit" class="btn-save"><i class="bi bi-floppy-fill me-2"></i> Save Changes</button>
                <a href="{{ route('candidates.index') }}" class="btn btn-outline-secondary fw-bold rounded-pill px-4" style="font-size:var(--fs-sm);">Cancel</a>
            </div>
        </form>
    </div>

    {{-- ── Last Activity ────────────────────────────────────────────── --}}
    @if($lastVote)
    <div style="background:var(--bg-surface);border:1px solid var(--border-col);border-radius:14px;padding:16px 20px;margin-bottom:24px;transition:background .3s,border-color .3s;">
        <div style="font-size:10px;font-weight:800;color:var(--text-faint);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">
            <i class="bi bi-shield-check me-1 text-success"></i> Last Activity
        </div>
        <div style="font-size:var(--fs-sm);color:var(--text-muted);">
            Last ballot cast on <strong style="color:var(--text-heading);">{{ $lastVote->created_at->format('F d, Y \a\t h:i A') }}</strong>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════════════════════
   PASSWORD HELPERS
═══════════════════════════════════════════════════════════════════════ */
function togglePwd(id, btn) {
    const el = document.getElementById(id);
    const ic = btn.querySelector('i');
    el.type  = el.type === 'password' ? 'text' : 'password';
    ic.className = el.type === 'text' ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
}
function checkStrength(v) {
    const colors = ['#ef4444','#f59e0b','#3b82f6','#10b981'];
    let s = 0;
    if (v.length >= 8)           s++;
    if (/[A-Z]/.test(v))         s++;
    if (/[0-9]/.test(v))         s++;
    if (/[^A-Za-z0-9]/.test(v)) s++;
    ['s1','s2','s3','s4'].forEach((id,i) => {
        document.getElementById(id).style.background = i < s ? colors[s-1] : 'var(--border-col)';
    });
}

/* ═══════════════════════════════════════════════════════════════════════
   DYSLEXIA FONT
═══════════════════════════════════════════════════════════════════════ */
function setDyslexia(on) {
    Prefs.set('dyslexia', on ? '1' : '0');
    applyDyslexia(on);
}
function applyDyslexia(on) {
    let link = document.getElementById('dyslexia-font');
    if (on) {
        if (!link) {
            link = document.createElement('link');
            link.id   = 'dyslexia-font';
            link.rel  = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/open-dyslexic@1.0.3/open-dyslexic-regular.css';
            document.head.appendChild(link);
        }
        document.body.style.fontFamily = "'OpenDyslexic', sans-serif";
    } else {
        if (link) link.remove();
        document.body.style.fontFamily = "'DM Sans', sans-serif";
    }
    document.getElementById('dyslexiaToggle').checked = on;
}

/* ═══════════════════════════════════════════════════════════════════════
   COMPACT MODE
═══════════════════════════════════════════════════════════════════════ */
function setCompact(on) {
    Prefs.set('compact', on ? '1' : '0');
    document.documentElement.setAttribute('data-compact', on ? '1' : '0');
    document.getElementById('compactToggle').checked = on;
}

/* ═══════════════════════════════════════════════════════════════════════
   SYSTEM THEME
═══════════════════════════════════════════════════════════════════════ */
function applySystemTheme() {
    Prefs.set('theme', 'system');
    const dark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
    syncToggleStates();
    syncPrefUI();
}

/* ═══════════════════════════════════════════════════════════════════════
   RESET ALL PREFERENCES
═══════════════════════════════════════════════════════════════════════ */
function resetPrefs() {
    Swal.fire({
        title: 'Reset Preferences?',
        text: 'All display settings will return to their defaults.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#8a1538',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Reset',
        cancelButtonText: 'Cancel'
    }).then(r => {
        if (r.isConfirmed) {
            localStorage.removeItem('um_prefs');
            location.reload();
        }
    });
}

/* ═══════════════════════════════════════════════════════════════════════
   FONT PREVIEW
═══════════════════════════════════════════════════════════════════════ */
const fontSizes = { sm:'20px', md:'28px', lg:'36px', xl:'44px' };
function updateFontPreview() {
    const fs  = Prefs.get().fontSize || 'md';
    const el  = document.getElementById('fontPreview');
    if (el) el.style.fontSize = fontSizes[fs] || '28px';
}

/* ═══════════════════════════════════════════════════════════════════════
   SYNC TOGGLE STATES (checkboxes)
═══════════════════════════════════════════════════════════════════════ */
function syncToggleStates() {
    const p = Prefs.get();
    const dt = document.getElementById('darkToggle');
    const ct = document.getElementById('contrastToggle');
    const mt = document.getElementById('motionToggle');
    const dy = document.getElementById('dyslexiaToggle');
    const cm = document.getElementById('compactToggle');
    if (dt) dt.checked = (p.theme    === 'dark');
    if (ct) ct.checked = (p.contrast === 'high');
    if (mt) mt.checked = (p.motion   === 'reduce');
    if (dy) dy.checked = (p.dyslexia === '1');
    if (cm) cm.checked = (p.compact  === '1');
}

/* ═══════════════════════════════════════════════════════════════════════
   BOOT
═══════════════════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {
    const p = Prefs.get();

    // Apply extras not handled by app.blade inline script
    if (p.dyslexia === '1') applyDyslexia(true);
    if (p.compact  === '1') document.documentElement.setAttribute('data-compact','1');

    syncToggleStates();
    syncPrefUI();
    updateFontPreview();

    // Listen for font size button clicks to refresh preview
    document.querySelectorAll('[data-pref-key="fontSize"]').forEach(btn => {
        btn.addEventListener('click', () => { setTimeout(updateFontPreview, 50); });
    });
});
</script>

{{-- Compact mode CSS injected dynamically --}}
<style>
[data-compact="1"] .content-area   { padding-top:20px !important; padding-bottom:20px !important; }
[data-compact="1"] .nav-item       { padding:9px 14px !important; margin-bottom:1px !important; }
[data-compact="1"] .card-section-header { padding:14px 20px !important; }
[data-compact="1"] .pref-group     { padding:14px !important; }
[data-compact="1"] .dash-card, [data-compact="1"] .profile-card { border-radius:14px !important; }
</style>
@endpush