<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seller Terminal') | Pro-Stock-Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Laila:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">   
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --primary: #6366f1;
            --primary-light: #818cf8;
            --bg-light: #f1f5f9;
            --text-gray: #64748b;
            --success: #10b981;
            --white: #ffffff;
            --orange: #f59e0b;
        }

        body {
            font-family: 'Laila', sans-serif;
            margin: 0;
            display: flex;
            background-color: var(--bg-light);
        }

        .sidebar {
            width: 270px;
            background-color: var(--sidebar-bg);
            height: 100vh;
            color: white;
            position: fixed;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-header {
            padding: 40px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .logo-icon {
            background: linear-gradient(135deg, var(--primary), #a5b4fc);
            width: 55px; height: 55px;
            margin: 0 auto 15px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 14px; font-size: 26px;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .sidebar-menu { flex-grow: 1; padding: 25px 0; }

        .menu-item {
            padding: 14px 25px;
            display: flex; align-items: center;
            color: var(--text-gray);
            text-decoration: none;
            transition: 0.3s;
            font-weight: 400;
        }

        .menu-item i { margin-right: 15px; width: 20px; font-size: 18px; }

        .menu-item:hover, .menu-item.active {
            color: white;
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.15), transparent);
            border-left: 4px solid var(--primary);
        }

        .main-content {
            margin-left: 270px;
            width: calc(100% - 270px);
            min-height: 100vh;
        }

        .top-navbar {
            background: var(--white);
            padding: 15px 35px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .premium-card {
            background: var(--white);
            border-radius: 22px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid #edf2f7;
            position: relative; overflow: hidden;
            margin-bottom: 25px;
        }

        .logout-btn {
            margin: 20px; padding: 14px;
            background: rgba(239, 68, 68, 0.08);
            color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.15);
            border-radius: 12px; cursor: pointer;
            font-family: 'Laila'; font-weight: 700; transition: 0.3s;
        }
        .logout-btn:hover { background: #ef4444; color: white; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon"><i class="fas fa-cubes"></i></div>
            <div style="font-weight: 700; letter-spacing: 1px; font-size: 1.1rem; text-transform: uppercase;">Pro Stock Manager</div>
            <small style="color: var(--text-gray); font-size: 10px;">Seller Management System</small>
        </div>
        
        <div class="sidebar-menu">
            <a href="{{ route('seller.dashboard') }}" class="menu-item {{ Request::routeIs('seller.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard Central
            </a>
            <a href="{{ route('products.add') }}" class="menu-item {{ Request::routeIs('products.add') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i> Add New Product
            </a>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn" style="width: 85%; margin: 20px auto; display: block; font-size:15px;">
                <i class="fas fa-power-off"></i> Secure Logout
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="top-navbar">
            <div style="color: var(--primary); font-weight: 700; font-size: 14px;">
                <i class="fas fa-shield-alt"></i> SECURE TERMINAL: {{ strtoupper(date('l, d M Y')) }}
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="text-align: right;">
                    <div style="font-weight: 700; font-size: 15px; color: var(--sidebar-bg);">{{ Auth::user()->name }}</div>
                    <small style="color: var(--success); font-weight: 700; font-size: 10px; text-transform: uppercase;">Verified Seller</small>
                </div>
                <div style="width: 45px; height: 45px; background: linear-gradient(135deg, var(--primary), var(--primary-light)); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        <div style="padding: 35px;">
            <div class="premium-card" style="border-left: 6px solid var(--primary);">
                <h2 style="margin: 0; font-size: 26px; color: var(--sidebar-bg); font-weight:bold;">Welcome Back, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
                <p style="color: var(--text-gray); margin: 15px 0 0; line-height: 1.8; font-size: 15px;">
                    The <strong>Inventory Vault</strong> is optimized for your operations. Use the terminal below to manage your product categories and registered brands.
                </p>
            </div>

            @yield('content')
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>  
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>