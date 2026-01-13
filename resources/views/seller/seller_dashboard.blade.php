<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard | Pro-Stock-Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Laila:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }
        .stat-card {
            background: var(--white);
            padding: 25px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.02);
            border-bottom: 4px solid transparent;
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon {
            width: 60px; height: 60px;
            border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
        }
        .icon-blue { background: #eef2ff; color: var(--primary); }
        .icon-orange { background: #fff7ed; color: var(--orange); }

        .stat-info h3 { margin: 0; font-size: 13px; color: var(--text-gray); text-transform: uppercase; letter-spacing: 1px; }
        .stat-info div { font-size: 28px; font-weight: 700; color: var(--sidebar-bg); }

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
            <a href="{{ route('seller.dashboard') }}" class="menu-item {{ request()->is('seller/dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard Central
            </a>
            <a href="{{ route('products.add') }}" class="menu-item {{ request()->is('seller/add-product') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i> Add New Product
            </a>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn" style="width: 85%;font-size:15px;">
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
                <h2 style="margin: 0; font-size: 26px; color: var(--sidebar-bg);">Welcome Back, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
                <p style="color: var(--text-gray); margin: 15px 0 0; line-height: 1.8; font-size: 15px;">
                    The <strong>Inventory Vault</strong> is optimized for your operations. Use the terminal below to manage your product categories and registered brands.
                </p>
            </div>

            <div class="stats-grid">
                <div class="stat-card" style="border-bottom-color: var(--primary);">
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stat-info">
                        <h3>My Products</h3>
                        <div>{{ $productCount }}</div>
                    </div>
                </div>

                <div class="stat-card" style="border-bottom-color: var(--orange);">
                    <div class="stat-icon icon-orange">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Registered Brands</h3>
                        <div>{{ $brandCount }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>