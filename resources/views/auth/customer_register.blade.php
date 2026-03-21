<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Join | Pro Stock Manager</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #818cf8;
            --bg-color: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-container {
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 50px;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(255,255,255,0.7);
        }

        .header { text-align: center; margin-bottom: 40px; }
        .logo-box { width: 60px; height: 60px; background: var(--primary); color: white; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3); }

        .form-group { margin-bottom: 24px; }
        label { display: block; font-size: 14px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; }
        
        input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #f1f5f9;
            border-radius: 14px;
            font-family: inherit;
            font-size: 15px;
            transition: all 0.3s;
            box-sizing: border-box;
            background: #f8fafc;
        }
        input:focus { border-color: var(--primary); outline: none; background: white; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.2);
        }
        .btn-submit:hover { background: #4f46e5; transform: translateY(-2px); box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.2); }

        .footer-links { margin-top: 30px; text-align: center; font-size: 14px; color: var(--text-muted); }
        .footer-links a { color: var(--primary); text-decoration: none; font-weight: 700; }

        @media screen and (max-width: 500px) {
            .card { padding: 30px; }
            div[style*="display: flex"] { flex-direction: column !important; gap: 0 !important; }
            div[style*="margin-bottom: 24px"] { margin-bottom: 0 !important; }
            .form-group, div[style*="flex: 1"] { margin-bottom: 20px !important; }
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="card">
        <div class="header">
            <div class="logo-box"><i class="fas fa-shopping-bag"></i></div>
            <h1 style="margin:0; font-size: 24px; font-weight: 800; color: var(--text-dark);">Join the Ecosystem</h1>
            <p style="margin:8px 0 0; color: var(--text-muted);">Create your customer profile today.</p>
        </div>

        @if(session('error'))
            <div style="background: #fef2f2; color: #dc2626; padding: 15px; border-radius: 14px; margin-bottom: 20px; font-size: 14px; border: 1px solid #fee2e2;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fff1f2; color: #e11d48; padding: 15px; border-radius: 16px; margin-bottom: 24px; font-size: 13px; border: 1px solid #ffe4e6; line-height: 1.6;">
                <div style="font-weight: 800; margin-bottom: 4px;"><i class="fas fa-exclamation-circle"></i> Registration Issues:</div>
                <ul style="margin:0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="John Doe" required value="{{ old('name') }}">
            </div>
            
            <div style="display: flex; gap: 15px; margin-bottom: 24px;">
                <div style="flex: 1;">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="john@example.com" required value="{{ old('email') }}">
                </div>
                <div style="flex: 1;">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile_no" placeholder="+91 98765 43210" required value="{{ old('mobile_no') }}">
                </div>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 24px;">
                <div style="flex: 1;">
                    <label>Country</label>
                    <input type="text" name="country" placeholder="India" required value="{{ old('country') }}">
                </div>
                <div style="flex: 1;">
                    <label>State / Region</label>
                    <input type="text" name="state" placeholder="Maharashtra" required value="{{ old('state') }}">
                </div>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 24px;">
                <div style="flex: 1;">
                    <label>Secure Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <div style="flex: 1;">
                    <label>Confirm Entry</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Create My Profile</button>
        </form>

        <div class="footer-links">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
        </div>
    </div>
</div>

</body>
</html>
