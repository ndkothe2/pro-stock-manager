<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Pro Stock Manager</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Laila:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Laila', sans-serif;
            background-color: var(--bg-color);
            background-image: radial-gradient(circle at 2px 2px, #e2e8f0 1px, transparent 0);
            background-size: 30px 30px;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: var(--card-bg);
            padding: 45px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .header {
            text-align: center;
            margin-bottom: 35px;
        }

        .header h2 {
            color: var(--primary);
            font-size: 30px;
            font-weight: 700;
            margin: 0 0 8px 0;
            letter-spacing: -0.5px;
        }

        .header p {
            color: var(--text-muted);
            font-size: 15px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i.input-icon {
            position: absolute;
            left: 16px;
            color: var(--text-muted);
            font-size: 17px;
        }

        input {
            width: 100%;
            padding: 13px 45px 13px 48px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Laila', sans-serif;
            color: var(--text-dark);
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            cursor: pointer;
            color: var(--text-muted);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 600;
            font-family: 'Laila', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-google {
            width: 100%;
            padding: 12px;
            background: white;
            color: #444;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Laila', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-google:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn-google img {
            width: 18px;
            margin-right: 12px;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: var(--text-muted);
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .divider:not(:empty)::before {
            margin-right: .5em;
        }

        .divider:not(:empty)::after {
            margin-left: .5em;
        }

        .alert-error {
            background: #fff1f2;
            color: #be123c;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
        }

        .footer {
            text-align: center;
            margin-top: 35px;
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .footer b {
            color: var(--text-dark);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="header">
        <h2>Pro Stock Manager</h2>
        <p>Sign in to your account</p>
    </div>

    @if ($errors->any() || session('error'))
        <div class="alert-error">
            <i class="fas fa-circle-exclamation" style="margin-right: 10px;"></i>
            {{ $errors->any() ? $errors->first() : session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <div class="form-group">
            <label>Email Address</label>
            <div class="input-wrapper">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" name="email" placeholder="name@email.com" required autofocus value="{{ old('email') }}" autocomplete="off">
            </div>
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="new-password">
                <i class="fa-solid fa-eye-slash toggle-password" id="eyeIcon"></i>
            </div>
        </div>

        <button type="submit" class="btn-login">Sign In</button>

        <div class="divider">OR</div>

        <a href="{{ url('/auth/google') }}" class="btn-google">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Logo">
            Sign in with Google
        </a>

        <div style="text-align: center; margin-top: 25px; font-size: 14px; color: var(--text-muted);">
            Don't have an account? <a href="{{ route('register') }}" style="color: var(--primary); text-decoration: none; font-weight: 700;">Join as Customer</a>
        </div>
    </form>

    <div class="footer">
        © 2026 <b>Nikhil Kothe</b> | Pro Stock Manager | Secure Access
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    eyeIcon.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            passwordInput.type = 'password';
            this.classList.replace('fa-eye', 'fa-eye-slash');
        }
    });
</script>

</body>
</html>