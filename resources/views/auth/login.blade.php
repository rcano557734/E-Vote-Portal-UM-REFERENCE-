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
            --um-maroon: #8a1538;
            --um-maroon-dark: #630f28;
            --um-maroon-light: #fff0f2;
            --um-gold: #fdb813;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: radial-gradient(circle at top left, var(--um-maroon-light) 0%, #f8fafc 40%, #e2e8f0 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            overflow: hidden;
            color: #1e293b;
        }

        /* Typography */
        h1 { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; font-size: 36px; margin-bottom: 10px; color: #0f172a; letter-spacing: -0.03em;}
        p { font-size: 15px; font-weight: 400; line-height: 1.6; letter-spacing: 0.2px; margin: 15px 0 30px; color: #64748b; }
        
        /* Links & Buttons */
        a { color: var(--um-maroon); font-size: 14px; text-decoration: none; margin: 15px 0; font-weight: 700; transition: color 0.2s;}
        a:hover { color: var(--um-maroon-dark); text-decoration: underline;}
        
        button {
            border-radius: 12px;
            border: 1px solid var(--um-maroon);
            background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark));
            color: #FFFFFF;
            font-size: 15px;
            font-weight: 800;
            padding: 16px 48px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in, box-shadow 0.2s;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            box-shadow: 0 6px 20px rgba(138,21,56,0.25);
        }
        button:active { transform: scale(0.95); }
        button:hover { box-shadow: 0 10px 25px rgba(138,21,56,0.4); transform: translateY(-2px); }
        
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
            padding: 0 60px;
            height: 100%;
            text-align: center;
        }
        .input-group {
            position: relative;
            width: 100%;
            margin: 10px 0;
        }
        .input-group i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px;
            transition: color 0.2s;
        }
        input {
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 15px 16px 50px;
            width: 100%;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            color: #1e293b;
            transition: all 0.2s;
            outline: none;
        }
        input:focus { border-color: var(--um-maroon); background: #ffffff; box-shadow: 0 0 0 4px rgba(138,21,56, 0.1); }
        input:focus + i, .input-group:focus-within i { color: var(--um-maroon); }

        /* Error Messages */
        .error-text { color: #ef4444; font-size: 13px; margin-top: 4px; text-align: left; width: 100%; font-weight: 600; display: block; padding-left: 10px;}

        /* The Main Container (Made Moderately Bigger) */
        .container {
            background-color: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1), 0 0 0 1px rgba(0,0,0,0.02);
            position: relative;
            overflow: hidden;
            width: 1050px; 
            max-width: 100%;
            min-height: 680px; 
            transition: transform 0.3s;
        }

        /* Container Sections */
        .form-container { position: absolute; top: 0; height: 100%; transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        
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
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            z-index: 100;
        }
        .container.right-panel-active .overlay-container { transform: translateX(-100%); }

        .overlay {
            background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark));
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        /* Decorative UM Gold circles */
        .overlay::before { content: ''; position: absolute; top: -50px; left: -50px; width: 250px; height: 250px; background: rgba(253, 184, 19, 0.1); border-radius: 50%; }
        .overlay::after { content: ''; position: absolute; bottom: -80px; right: -50px; width: 350px; height: 350px; background: rgba(255, 255, 255, 0.05); border-radius: 50%; }

        .container.right-panel-active .overlay { transform: translateX(50%); }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            z-index: 10;
        }
        .overlay-panel h1 { color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .overlay-panel p { color: var(--um-maroon-light); font-size: 16px;}

        .overlay-left { transform: translateX(-20%); }
        .container.right-panel-active .overlay-left { transform: translateX(0); }
        .overlay-right { right: 0; transform: translateX(0); }
        .container.right-panel-active .overlay-right { transform: translateX(20%); }

        /* Top Logo */
        .top-logo { position: absolute; top: 40px; left: 50px; display: flex; align-items: center; gap: 12px; text-decoration: none; z-index: 200; transition: transform 0.2s;}
        .top-logo:hover { transform: scale(1.02); }
        
        /* Responsive Mobile Fallback */
        @media (max-width: 768px) {
            .container { width: 100%; min-height: 100vh; border-radius: 0; }
            .sign-in-container, .sign-up-container { width: 100%; left: 0; }
            .overlay-container { display: none; }
            .container.right-panel-active .sign-in-container { transform: translateX(0); display: none; }
            .container.right-panel-active .sign-up-container { transform: translateX(0); opacity: 1; }
            .mobile-toggle { display: block !important; }
        }
        .mobile-toggle { display: none; background: transparent; border: none; color: var(--um-maroon); font-weight: 800; box-shadow: none; padding: 10px; margin-top: 20px;}
        .mobile-toggle:hover { box-shadow: none; color: var(--um-maroon-dark); background: var(--um-maroon-light);}
    </style>
</head>
<body>

    <a href="/" class="top-logo">
        <div style="width: 38px; height: 38px; background: linear-gradient(135deg, var(--um-maroon), var(--um-maroon-dark)); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--um-gold); font-weight: 900; font-family: 'Bricolage Grotesque'; font-size: 18px; box-shadow: 0 4px 10px rgba(138,21,56,0.3);">UM</div>
        <span style="font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800; font-size: 22px; color: #0f172a; letter-spacing: -0.02em;">E-Vote <span style="color: var(--um-maroon);">Portal</span></span>
    </a>

    @php
        $isRegister = request()->routeIs('register') || $errors->has('name');
    @endphp

    <div class="container {{ $isRegister ? 'right-panel-active' : '' }}" id="container">
        
        <div class="form-container sign-up-container">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h1>Create Account</h1>
                <p>Register as a verified UM student to cast your official ballot securely.</p>
                
                <div class="input-group">
                    <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required autofocus />
                    <i class="bi bi-person-fill"></i>
                </div>
                @error('name') <span class="error-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror

                <div class="input-group">
                    <input type="email" name="email" placeholder="UM Email Address" value="{{ old('email') }}" required />
                    <i class="bi bi-envelope-fill"></i>
                </div>
                @error('email') <span class="error-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required />
                    <i class="bi bi-lock-fill"></i>
                </div>
                @error('password') <span class="error-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror

                <div class="input-group">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <input type="hidden" name="role_id" value="3">
                <input type="hidden" name="is_verified" value="0">

                <button type="submit" class="mt-4">Register Account</button>
                <button type="button" class="mobile-toggle" id="mobileSignIn">Already have an account? Sign In</button>
            </form>
        </div>

        <div class="form-container sign-in-container">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h1>Welcome Back</h1>
                <p>Enter your student credentials to access your dashboard and cast your vote.</p>
                
                <div class="input-group">
                    <input type="email" name="email" placeholder="UM Email Address" value="{{ old('email') }}" required autofocus />
                    <i class="bi bi-envelope-fill"></i>
                </div>
                @error('email') <span class="error-text"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span> @enderror

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required />
                    <i class="bi bi-lock-fill"></i>
                </div>

                <a href="#" style="align-self: flex-end; margin-right: 10px;">Forgot your password?</a>
                <button type="submit" class="mt-2">Secure Login</button>
                <button type="button" class="mobile-toggle" id="mobileSignUp">Don't have an account? Sign Up</button>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <div style="font-size: 50px; color: var(--um-gold); margin-bottom: 10px;"><i class="bi bi-person-check-fill"></i></div>
                    <h1>Already Registered?</h1>
                    <p>If you already have a verified student account, log in to access the election portal.</p>
                    <button class="ghost mt-3" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <div style="font-size: 50px; color: var(--um-gold); margin-bottom: 10px;"><i class="bi bi-person-vcard-fill"></i></div>
                    <h1>New Student?</h1>
                    <p>Enter your personal details to register for the upcoming UM Student Council elections.</p>
                    <button class="ghost mt-3" id="signUp">Register Now</button>
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

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
            window.history.pushState({}, '', '/register');
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
            window.history.pushState({}, '', '/login');
        });

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