<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoteSystem - Authentication</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,700;12..96,800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: radial-gradient(circle at top left, #e0f2fe 0%, #f8fafc 35%, #ecfdf5 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            overflow: hidden;
            color: #1e293b;
        }

        /* Typography */
        h1 { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; font-size: 32px; margin-bottom: 10px; color: #0f172a; letter-spacing: -0.03em;}
        p { font-size: 14px; font-weight: 400; line-height: 20px; letter-spacing: 0.5px; margin: 20px 0 30px; color: #64748b; }
        
        /* Links & Buttons */
        a { color: #16a34a; font-size: 14px; text-decoration: none; margin: 15px 0; font-weight: 600; transition: color 0.2s;}
        a:hover { color: #15803d; }
        button {
            border-radius: 12px;
            border: 1px solid #16a34a;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #FFFFFF;
            font-size: 14px;
            font-weight: 700;
            padding: 14px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in, box-shadow 0.2s;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            box-shadow: 0 4px 15px rgba(22,163,74,0.25);
        }
        button:active { transform: scale(0.95); }
        button:hover { box-shadow: 0 8px 25px rgba(22,163,74,0.35); transform: translateY(-2px); }
        button.ghost {
            background: transparent;
            border-color: #FFFFFF;
            box-shadow: none;
        }
        button.ghost:hover { background: rgba(255,255,255,0.1); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }

        /* Forms */
        form {
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
        }
        .input-group {
            position: relative;
            width: 100%;
            margin: 8px 0;
        }
        .input-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px;
        }
        input {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 15px 14px 45px;
            width: 100%;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: #1e293b;
            transition: border-color 0.2s, background 0.2s;
            outline: none;
        }
        input:focus { border-color: #16a34a; background: #ffffff; box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.1); }

        /* Error Messages */
        .error-text { color: #ef4444; font-size: 12px; margin-top: 4px; text-align: left; width: 100%; font-weight: 500; display: block; }

        /* The Main Container */
        .container {
            background-color: #fff;
            border-radius: 24px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.02);
            position: relative;
            overflow: hidden;
            width: 1050px; /* Increased from 900px */
            max-width: 100%;
            min-height: 680px; /* Increased from 600px */
            transition: transform 0.3s;
        }

        /* Container Sections */
        .form-container { position: absolute; top: 0; height: 100%; transition: all 0.6s ease-in-out; }
        
        /* Sign In Side */
        .sign-in-container { left: 0; width: 50%; z-index: 2; }
        .container.right-panel-active .sign-in-container { transform: translateX(100%); opacity: 0; }
        
        /* Sign Up Side */
        .sign-up-container { left: 0; width: 50%; opacity: 0; z-index: 1; }
        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }

        /* The Sliding Overlay */
        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }
        .container.right-panel-active .overlay-container { transform: translateX(-100%); }

        .overlay {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }
        /* Add decorative circles to overlay */
        .overlay::before { content: ''; position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; }
        .overlay::after { content: ''; position: absolute; bottom: -80px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; }

        .container.right-panel-active .overlay { transform: translateX(50%); }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
            z-index: 10;
        }
        .overlay-panel h1 { color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .overlay-panel p { color: #dcfce7; }

        .overlay-left { transform: translateX(-20%); }
        .container.right-panel-active .overlay-left { transform: translateX(0); }
        .overlay-right { right: 0; transform: translateX(0); }
        .container.right-panel-active .overlay-right { transform: translateX(20%); }

        /* Top Logo */
        .top-logo { position: absolute; top: 30px; left: 40px; display: flex; align-items: center; gap: 10px; text-decoration: none; z-index: 200; }
        
        /* Responsive Mobile Fallback */
        @media (max-width: 768px) {
            .container { width: 100%; min-height: 100vh; border-radius: 0; }
            .sign-in-container, .sign-up-container { width: 100%; left: 0; }
            .overlay-container { display: none; }
            .container.right-panel-active .sign-in-container { transform: translateX(0); display: none; }
            .container.right-panel-active .sign-up-container { transform: translateX(0); opacity: 1; }
            .mobile-toggle { display: block !important; }
        }
        .mobile-toggle { display: none; background: transparent; border: none; color: #16a34a; font-weight: 700; box-shadow: none; padding: 10px; margin-top: 20px;}
        .mobile-toggle:hover { box-shadow: none; color: #15803d; background: #f0fdf4;}
    </style>
</head>
<body>

    <a href="/" class="top-logo">
        <svg width="30" height="30" viewBox="0 0 34 34" fill="none">
            <rect width="34" height="34" rx="9" fill="#16a34a"/>
            <path d="M9 17.5L14.5 23L25 12" stroke="white" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="17" cy="17" r="12" stroke="white" stroke-width="1.4" fill="none" opacity="0.35"/>
        </svg>
        <span style="font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; font-size: 18px; color: #111; letter-spacing: -0.02em;">Vote<span style="color: #16a34a;">System</span></span>
    </a>

    @php
        $isRegister = request()->routeIs('register') || $errors->has('name');
    @endphp

    <div class="container {{ $isRegister ? 'right-panel-active' : '' }}" id="container">
        
        <div class="form-container sign-up-container">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h1>Create Account</h1>
                <p>Register as a verified voter to cast your official ballot securely.</p>
                
                <div class="input-group">
                    <i class="bi bi-person"></i>
                    <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required autofocus />
                </div>
                @error('name') <span class="error-text">{{ $message }}</span> @enderror

                <div class="input-group">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required />
                </div>
                @error('email') <span class="error-text">{{ $message }}</span> @enderror

                <div class="input-group">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" placeholder="Password" required />
                </div>
                @error('password') <span class="error-text">{{ $message }}</span> @enderror

                <div class="input-group">
                    <i class="bi bi-shield-lock"></i>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
                </div>

                <input type="hidden" name="role_id" value="3">
                <input type="hidden" name="is_verified" value="0">

                <button type="submit" class="mt-4">Sign Up</button>

                <button type="button" class="mobile-toggle" id="mobileSignIn">Already have an account? Sign In</button>
            </form>
        </div>

        <div class="form-container sign-in-container">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h1>Welcome Back</h1>
                <p>Enter your credentials to access your dashboard and cast your vote.</p>
                
                <div class="input-group">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required autofocus />
                </div>
                @error('email') <span class="error-text">{{ $message }}</span> @enderror

                <div class="input-group">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" placeholder="Password" required />
                </div>

                <a href="#">Forgot your password?</a>
                <button type="submit">Sign In</button>

                <button type="button" class="mobile-toggle" id="mobileSignUp">Don't have an account? Sign Up</button>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Already Registered?</h1>
                    <p>If you already have a verified account, log in to access the election portal.</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>New Voter?</h1>
                    <p>Enter your personal details to register for the upcoming secure elections.</p>
                    <button class="ghost" id="signUp">Register Now</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const mobileSignUp = document.getElementById('mobileSignUp');
        const mobileSignIn = document.getElementById('mobileSignIn');
        const container = document.getElementById('container');

        // Desktop Sliders
        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
            window.history.pushState({}, '', '/register'); // Update URL to /register
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
            window.history.pushState({}, '', '/login'); // Update URL to /login
        });

        // Mobile Toggles
        mobileSignUp.addEventListener('click', () => {
            container.classList.add("right-panel-active");
            window.history.pushState({}, '', '/register');
        });

        mobileSignIn.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
            window.history.pushState({}, '', '/login');
        });
    </script>
</body>
</html>