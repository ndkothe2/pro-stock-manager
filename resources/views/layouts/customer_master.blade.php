<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | Pro Stock Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary: #6366F1;
            --primary-dark: #4F46E5;
            --sidebar-width: 280px;
            --bg-color: #F8FAFC;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            display: flex;
            min-height: 100vh;
            color: #1e293b;
        }

        /* Premium Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: #0F172A;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 40px 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-box {
            background: linear-gradient(135deg, #6366F1, #A855F7);
            width: 45px;
            height: 45px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .sidebar-nav {
            padding: 20px 20px;
            flex-grow: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 20px;
            color: #94A3B8;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
            font-size: 15px;
            font-weight: 600;
            transition: 0.3s;
        }

        .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
        .nav-item.active { background: var(--primary); color: white; box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3); }

        .sidebar-footer { padding: 30px; border-top: 1px solid rgba(255,255,255,0.05); }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 900;
            border-bottom: 1px solid #E2E8F0;
        }

        .search-container {
            flex: 1;
            max-width: 500px;
            position: relative;
        }

        .status-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #F0FDF4;
            color: #166534;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid #DCFCE7;
        }

        .avatar-box {
            width: 44px;
            height: 44px;
            background: #E2E8F0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #475569;
        }

        /* Footer */
        .page-footer {
            background: white;
            padding: 60px 40px 40px;
            margin-top: auto;
            border-top: 1px solid #E2E8F0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-col h4 { margin: 0 0 20px; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: #94A3B8; }
        .footer-col ul { list-style: none; padding: 0; margin: 0; }
        .footer-col ul li { margin-bottom: 12px; }
        .footer-col ul li a { color: #64748B; text-decoration: none; font-size: 14px; transition: 0.2s; }
        .footer-col ul li a:hover { color: var(--primary); }

        .social-icons { display: flex; gap: 15px; margin-top: 20px; }
        .social-icons a { width: 36px; height: 36px; background: #F1F5F9; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #64748B; transition: 0.3s; }
        .social-icons a:hover { background: var(--primary); color: white; transform: translateY(-3px); }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo-box"><i class="fas fa-microchip"></i></div>
            <div style="font-weight: 800; font-size: 20px; color: white; letter-spacing: -0.5px;">ProStock <span style="color: var(--primary);">.</span></div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('customer.dashboard') }}" class="nav-item {{ Request::is('customer/dashboard*') ? 'active' : '' }}">
                <i class="fas fa-compass"></i> Explore Items
            </a>
            <a href="#" class="nav-item"><i class="fas fa-layer-group"></i> My Orders</a>
            <a href="#" class="nav-item"><i class="fas fa-heart"></i> Saved Items</a>
            <a href="{{ route('customer.profile') }}" class="nav-item {{ Request::is('customer/profile*') ? 'active' : '' }}"><i class="fas fa-user-circle"></i>Account Setting</a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); width: 100%; padding: 12px; border-radius: 12px; color: #ef4444; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="fas fa-power-off"></i> Secure Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="main-content">
        <header class="top-navbar">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="status-badge">
                    <div style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%; box-shadow: 0 0 10px #22c55e;"></div>
                    System Online
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 30px;">
                <div style="display: flex; gap: 20px; align-items: center;">
                    <i class="far fa-bell" style="font-size: 20px; color: #64748B; cursor: pointer;"></i>
                    
                    <!-- Restored Cart Icon -->
                    <div style="position: relative; cursor: pointer;">
                        <i class="fas fa-shopping-cart" style="font-size: 20px; color: #64748B;"></i>
                        <span style="position: absolute; top: -8px; right: -10px; background: #EF4444; color: white; font-size: 10px; padding: 2px 6px; border-radius: 50%; border: 2px solid white; font-weight: 800;">0</span>
                    </div>
                </div>
                <div style="width: 1px; height: 30px; background: #E2E8F0;"></div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="text-align: right;">
                        <div style="font-weight: 700; font-size: 14px;">{{ Auth::guard('customer')->user()->name }}</div>
                        <div style="font-size: 11px; color: #94A3B8; text-transform: uppercase; font-weight: 700;">Customer Account</div>
                    </div>
                    @php 
                        $fullName = Auth::guard('customer')->user()->name;
                        $parts = explode(' ', $fullName);
                        $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                    @endphp
                    <div class="avatar-box" style="position: relative;">
                        {{ $initials }}
                        <!-- Online Status Dot -->
                        <div style="position: absolute; bottom: 2px; right: 2px; width: 10px; height: 10px; background: #22c55e; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 5px rgba(34, 197, 94, 0.5);"></div>
                    </div>
                </div>
            </div>
        </header>

        <div style="padding: 40px;">
            @yield('content')
        </div>

        <footer class="page-footer">
            <div class="footer-grid">
                <div class="footer-col" style="padding-right: 40px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                        <div class="logo-box" style="width: 30px; height: 30px; font-size: 14px;"><i class="fas fa-microchip"></i></div>
                        <span style="font-weight: 800; font-size: 18px;">ProStock Portal</span>
                    </div>
                    <p style="font-size: 14px; color: #64748B; line-height: 1.6;">Your gateway to high-valuation assets and premium merchant inventory. Authorized and encrypted by Mr. Nikhil Kothe.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Marketplace</h4>
                    <ul>
                        <li><a href="#">Explore Stocks</a></li>
                        <li><a href="#">Latest Category</a></li>
                        <li><a href="#">Global Search</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Knowledge</h4>
                    <ul>
                        <li><a href="#">Asset Guides</a></li>
                        <li><a href="#">Protocol Status</a></li>
                        <li><a href="#">Security Hub</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Support Center</h4>
                    <p style="font-size: 14px; color: #64748B;">For critical inquiries, contact Administrator:</p>
                    <a href="mailto:nikhil@example.com" style="color: var(--primary); font-weight: 700; text-decoration: none; font-size: 14px;">Mr. Nikhil Kothe</a>
                </div>
            </div>
            <div style="margin-top: 60px; padding-top: 30px; border-top: 1px solid #F1F5F9; text-align: center; color: #94A3B8; font-size: 13px;">
                &copy; {{ date('Y') }} Pro Stock Manager Ecosystem. All architectural rights reserved.
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @yield('extra_js')
</body>
</html>
