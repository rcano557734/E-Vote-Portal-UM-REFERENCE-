<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UM E-Vote | Authentication</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,700;12..96,800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">

    <style>
        :root {
            --um-maroon:       #8a1538;
            --um-maroon-dark:  #630f28;
            --um-maroon-light: #fff0f2;
            --um-gold:         #fdb813;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: radial-gradient(circle at top left, var(--um-maroon-light) 0%, #f8fafc 40%, #e2e8f0 100%);
            display: flex; justify-content: center; align-items: center; flex-direction: column;
            min-height: 100vh; overflow: hidden; color: #1e293b;
        }

        h1 { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; font-size: 34px; margin-bottom: 8px; color: #0f172a; letter-spacing: -0.03em; }
        p  { font-size: 14px; font-weight: 400; line-height: 1.6; margin: 10px 0 24px; color: #64748b; }

        a { color: var(--um-maroon); font-size: 13px; text-decoration: none; font-weight: 700; transition: color .2s; }
        a:hover { color: var(--um-maroon-dark); text-decoration: underline; }

        /* Primary button */
        .btn-auth {
            border-radius: 12px; border: 1px solid var(--um-maroon);
            background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark));
            color: #fff; font-size: 14px; font-weight: 800;
            padding: 15px 44px; letter-spacing: .08em; text-transform: uppercase;
            transition: transform 80ms ease-in, box-shadow .2s;
            cursor: pointer; font-family: 'DM Sans', sans-serif;
            box-shadow: 0 6px 20px rgba(138,21,56,.25); margin-top: 8px;
        }
        .btn-auth:active  { transform: scale(.95); }
        .btn-auth:hover   { box-shadow: 0 10px 25px rgba(138,21,56,.4); transform: translateY(-2px); }
        .btn-ghost {
            background: transparent; border: 1.5px solid #fff;
            color: #fff; box-shadow: none;
        }
        .btn-ghost:hover  { background: rgba(255,255,255,.12); box-shadow: 0 4px 15px rgba(0,0,0,.1); }

        /* Forms */
        form {
            background: #fff; display: flex; align-items: center; justify-content: center;
            flex-direction: column; padding: 0 56px; height: 100%; text-align: center;
        }

        .input-group { position: relative; width: 100%; margin: 8px 0; }
        .input-group i.icon {
            position: absolute; left: 17px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: 17px; pointer-events: none; transition: color .2s;
        }
        .input-group:focus-within i.icon { color: var(--um-maroon); }

        .input-group .toggle-pwd {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #94a3b8; cursor: pointer; padding: 0; font-size: 16px;
        }
        .input-group .toggle-pwd:hover { color: var(--um-maroon); }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px;
            padding: 15px 44px; width: 100%; font-family: 'DM Sans', sans-serif;
            font-size: 14px; color: #1e293b; transition: all .2s; outline: none;
        }
        input:focus         { border-color: var(--um-maroon); background: #fff; box-shadow: 0 0 0 4px rgba(138,21,56,.1); }
        input.input-error   { border-color: #ef4444 !important; background: #fef2f2 !important; }

        .error-text { color: #ef4444; font-size: 12px; margin-top: 3px; text-align: left; width: 100%; font-weight: 700; display: block; padding-left: 10px; }

        /* Strength bar */
        .strength-bar { display: flex; gap: 4px; width: 100%; margin-top: 5px; padding: 0 2px; }
        .strength-bar span { flex: 1; height: 4px; border-radius: 2px; background: #e2e8f0; transition: background .3s; }

        /* Container */
        .container {
            background: #fff; border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,.1), 0 0 0 1px rgba(0,0,0,.02);
            position: relative; overflow: hidden;
            width: 1060px; max-width: 100%; min-height: 700px;
            transition: transform .3s;
        }

        /* Sliding panels */
        .form-container { position: absolute; top: 0; height: 100%; transition: all .6s cubic-bezier(.25,.46,.45,.94); }

        .sign-in-container  { left: 0; width: 50%; z-index: 2; }
        .sign-up-container  { left: 0; width: 50%; opacity: 0; z-index: 1; }

        .container.right-panel-active .sign-in-container { transform: translateX(100%); opacity: 0; }
        .container.right-panel-active .sign-up-container {
            transform: translateX(100%); opacity: 1; z-index: 5; animation: show .6s;
        }
        @keyframes show {
            0%,49.99%  { opacity: 0; z-index: 1; }
            50%,100%   { opacity: 1; z-index: 5; }
        }

        /* Overlay */
        .overlay-container {
            position: absolute; top: 0; left: 50%; width: 50%; height: 100%;
            overflow: hidden; transition: transform .6s cubic-bezier(.25,.46,.45,.94); z-index: 100;
        }
        .container.right-panel-active .overlay-container { transform: translateX(-100%); }

        .overlay {
            background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark));
            color: #fff; position: relative; left: -100%; height: 100%; width: 200%;
            transform: translateX(0); transition: transform .6s cubic-bezier(.25,.46,.45,.94);
        }
        .overlay::before { content:''; position:absolute; top:-50px; left:-50px; width:250px; height:250px; background:rgba(253,184,19,.1); border-radius:50%; }
        .overlay::after  { content:''; position:absolute; bottom:-80px; right:-50px; width:350px; height:350px; background:rgba(255,255,255,.05); border-radius:50%; }
        .container.right-panel-active .overlay { transform: translateX(50%); }

        .overlay-panel {
            position: absolute; display: flex; align-items: center; justify-content: center;
            flex-direction: column; padding: 0 50px; text-align: center;
            top: 0; height: 100%; width: 50%;
            transition: transform .6s cubic-bezier(.25,.46,.45,.94); z-index: 10;
        }
        .overlay-panel h1 { color: white; text-shadow: 0 2px 10px rgba(0,0,0,.2); }
        .overlay-panel p  { color: var(--um-maroon-light); font-size: 15px; }
        .overlay-left  { transform: translateX(-20%); }
        .overlay-right { right: 0; transform: translateX(0); }
        .container.right-panel-active .overlay-left  { transform: translateX(0); }
        .container.right-panel-active .overlay-right { transform: translateX(20%); }

        /* Top logo */
        .top-logo { position: absolute; top: 36px; left: 44px; display: flex; align-items: center; gap: 12px; text-decoration: none; z-index: 200; transition: transform .2s; }
        .top-logo:hover { transform: scale(1.02); }

        /* Mobile */
        @media (max-width: 768px) {
            .container { width: 100%; min-height: 100vh; border-radius: 0; }
            .sign-in-container, .sign-up-container { width: 100%; left: 0; }
            .overlay-container { display: none; }
            .container.right-panel-active .sign-in-container { transform: translateX(0); display: none; }
            .container.right-panel-active .sign-up-container { transform: translateX(0); opacity: 1; }
            .mobile-toggle { display: block !important; }
            form { padding: 0 30px; }
        }
        .mobile-toggle {
            display: none; background: transparent; border: none;
            color: var(--um-maroon); font-weight: 800; font-size: 14px;
            box-shadow: none; padding: 10px; margin-top: 18px; cursor: pointer;
        }
        .mobile-toggle:hover { background: var(--um-maroon-light); border-radius: 8px; }
        .mt-3 { margin-top: 12px !important; }
        .mt-4 { margin-top: 18px !important; }
    </style>
</head>
<body>

<a href="/" class="top-logo">
    <div style="width:36px;height:36px;background:linear-gradient(135deg,var(--um-maroon),var(--um-maroon-dark));border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--um-gold);font-weight:900;font-family:'Bricolage Grotesque';font-size:17px;box-shadow:0 4px 10px rgba(138,21,56,.3);">UM</div>
    <span style="font-family:'Bricolage Grotesque';font-weight:800;font-size:21px;color:#0f172a;letter-spacing:-0.02em;">E-Vote <span style="color:var(--um-maroon);">Portal</span></span>
</a>

@php
    $isRegister = request()->routeIs('register') || $errors->has('name');
@endphp

<div class="container {{ $isRegister ? 'right-panel-active' : '' }}" id="container">

    {{-- ── REGISTER PANEL ─────────────────────────────────────── --}}
    <div class="form-container sign-up-container">
        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf
            <h1>Create Account</h1>
            <p>Register as a verified UM student to cast your official ballot securely.</p>

            {{-- Full Name --}}
            <div class="input-group">
                <input type="text" name="name" id="reg_name"
                       class="{{ $errors->has('name') ? 'input-error' : '' }}"
                       placeholder="Full Name" value="{{ old('name') }}" required autofocus autocomplete="name">
                <i class="bi bi-person-fill icon"></i>
            </div>
            @error('name') <span class="error-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror

            {{-- Email --}}
            <div class="input-group">
                <input type="email" name="email" id="reg_email"
                       class="{{ $errors->has('email') ? 'input-error' : '' }}"
                       placeholder="UM Email Address" value="{{ old('email') }}" required autocomplete="email">
                <i class="bi bi-envelope-fill icon"></i>
            </div>
            @error('email') <span class="error-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror

            {{-- Password + strength meter --}}
            <div class="input-group">
                <input type="password" name="password" id="reg_password"
                       class="{{ $errors->has('password') ? 'input-error' : '' }}"
                       placeholder="Password (min 8 chars)" required autocomplete="new-password"
                       style="padding-right: 44px;"
                       oninput="checkStrength(this.value)">
                <i class="bi bi-lock-fill icon"></i>
                <button type="button" class="toggle-pwd" onclick="togglePwd('reg_password', this)">
                    <i class="bi bi-eye-fill"></i>
                </button>
            </div>
            <div class="strength-bar" id="strengthBar">
                <span id="s1"></span><span id="s2"></span><span id="s3"></span><span id="s4"></span>
            </div>
            @error('password') <span class="error-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror

            {{-- Confirm Password --}}
            <div class="input-group">
                <input type="password" name="password_confirmation" id="reg_confirm"
                       placeholder="Confirm Password" required autocomplete="new-password"
                       style="padding-right: 44px;">
                <i class="bi bi-shield-lock-fill icon"></i>
                <button type="button" class="toggle-pwd" onclick="togglePwd('reg_confirm', this)">
                    <i class="bi bi-eye-fill"></i>
                </button>
            </div>

            <input type="hidden" name="role_id"     value="3">
            <input type="hidden" name="is_verified" value="0">

            <button type="submit" class="btn-auth mt-4">Register Account</button>
            <button type="button" class="mobile-toggle" id="mobileSignIn">
                Already have an account? <strong>Sign In</strong>
            </button>
        </form>
    </div>

    {{-- ── LOGIN PANEL ─────────────────────────────────────────── --}}
    <div class="form-container sign-in-container">
        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf
            <h1>Welcome Back</h1>
            <p>Enter your student credentials to access your dashboard and cast your vote.</p>

            {{-- Email --}}
            <div class="input-group">
                <input type="email" name="email" id="login_email"
                       class="{{ $errors->has('email') && !$isRegister ? 'input-error' : '' }}"
                       placeholder="UM Email Address" value="{{ old('email') }}" required autofocus autocomplete="email">
                <i class="bi bi-envelope-fill icon"></i>
            </div>
            @if(!$isRegister)
                @error('email') <span class="error-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror
            @endif

            {{-- Password --}}
            <div class="input-group">
                <input type="password" name="password" id="login_password"
                       placeholder="Password" required autocomplete="current-password"
                       style="padding-right: 44px;">
                <i class="bi bi-lock-fill icon"></i>
                <button type="button" class="toggle-pwd" onclick="togglePwd('login_password', this)">
                    <i class="bi bi-eye-fill"></i>
                </button>
            </div>

            <a href="#" style="align-self:flex-end;margin-right:8px;margin-top:8px;">Forgot password?</a>

            <button type="submit" class="btn-auth mt-3">Secure Login</button>
            <button type="button" class="mobile-toggle" id="mobileSignUp">
                Don't have an account? <strong>Sign Up</strong>
            </button>
        </form>
    </div>

    {{-- ── OVERLAY ─────────────────────────────────────────────── --}}
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <div style="font-size:48px;color:var(--um-gold);margin-bottom:12px;"><i class="bi bi-person-check-fill"></i></div>
                <h1>Already Registered?</h1>
                <p>Log in to access the election portal with your verified student account.</p>
                <button class="btn-auth btn-ghost mt-3" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <div style="font-size:48px;color:var(--um-gold);margin-bottom:12px;"><i class="bi bi-person-vcard-fill"></i></div>
                <h1>New Student?</h1>
                <p>Register your student account to participate in UM Council elections.</p>
                <button class="btn-auth btn-ghost mt-3" id="signUp">Register Now</button>
            </div>
        </div>
    </div>
</div>

<script>
// Panel switching
const container   = document.getElementById('container');
const signIn      = document.getElementById('signIn');
const signUp      = document.getElementById('signUp');
const mobileSignIn = document.getElementById('mobileSignIn');
const mobileSignUp = document.getElementById('mobileSignUp');

signUp.addEventListener('click', () => { container.classList.add('right-panel-active'); history.pushState({}, '', '/register'); });
signIn.addEventListener('click', () => { container.classList.remove('right-panel-active'); history.pushState({}, '', '/login'); });
mobileSignUp.addEventListener('click', () => { container.classList.add('right-panel-active'); history.pushState({}, '', '/register'); });
mobileSignIn.addEventListener('click', () => { container.classList.remove('right-panel-active'); history.pushState({}, '', '/login'); });

// Toggle password visibility
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'text' ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
}

// Password strength meter
function checkStrength(value) {
    const bars   = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'), document.getElementById('s4')];
    const colors = ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'];
    let score = 0;
    if (value.length >= 8)                    score++;
    if (/[A-Z]/.test(value))                  score++;
    if (/[0-9]/.test(value))                  score++;
    if (/[^A-Za-z0-9]/.test(value))          score++;

    bars.forEach((b, i) => {
        b.style.background = i < score ? colors[score - 1] : '#e2e8f0';
    });
}
</script>
</body>
</html>