<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Pro Stock Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Laila:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --primary: #4f46e5;
            --bg-light: #f8fafc;
            --text-gray: #64748b;
            --success: #22c55e;
            --white: #ffffff;
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
            justify-content: space-between;
            z-index: 100;
        }
        .sidebar-header {
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .logo-icon {
            background: linear-gradient(135deg, var(--primary), #818cf8);
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            font-size: 30px;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        }
        .brand-name {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #fff;
            text-transform: uppercase;
        }
        .sidebar-menu { flex-grow: 1; padding: 20px 0; }
        .menu-item {
            padding: 14px 25px;
            display: flex;
            align-items: center;
            color: var(--text-gray);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 15px;
        }
        .menu-item i { margin-right: 15px; width: 20px; font-size: 18px; }
        .menu-item:hover, .menu-item.active {
            background: linear-gradient(to right, rgba(79, 70, 229, 0.1), transparent);
            color: white;
            border-left: 4px solid var(--primary);
        }
        .menu-item.disabled-link {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }
        .sidebar-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.05); }
        .logout-btn {
            width: 100%;
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Laila', sans-serif;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: 0.3s;
        }
        .logout-btn:hover { background: #ef4444; color: white; }
        .main-content {
            margin-left: 270px;
            width: calc(100% - 270px);
            min-height: 100vh;
        }
        .top-navbar {
            background: var(--white);
            padding: 15px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .user-profile { display: flex; align-items: center; gap: 15px; }
        .avatar-box {
            width: 48px;
            height: 48px;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            border-radius: 12px;
            position: relative;
        }
        .active-dot {
            width: 12px;
            height: 12px;
            background: var(--success);
            border: 2px solid white;
            border-radius: 50%;
            position: absolute;
            top: -3px;
            right: -3px;
        }
        .welcome-banner {
            padding: 35px;
            background: var(--white);
            margin: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content-body { padding: 0 35px 35px; }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }
        .stat-card {
            background: var(--white);
            padding: 30px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        .bg-blue { background: #eef2ff; color: #4338ca; }
        .bg-green { background: #f0fdf4; color: #166534; }
        .bg-orange { background: #fff7ed; color: #9a3412; }
        .highlight-pin {
            color: var(--primary);
            font-weight: 700;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="sidebar-header">
                <div class="logo-icon"><i class="fas fa-cubes"></i></div>
                <div class="brand-name">Pro Stock Manager</div>
            </div>
            <div class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard Overview
                </a>
                <a href="{{ route('admin.sellers') }}" class="menu-item {{ Request::is('admin/sellers*') ? 'active' : '' }}">
                    <i class="fas fa-user-plus"></i> Seller Management
                </a>
                <a href="#" class="menu-item disabled-link">
                    <i class="fas fa-chart-pie"></i> Inventory Analytics
                </a>
                <a href="#" class="menu-item disabled-link">
                    <i class="fas fa-history"></i> System Logs
                </a>
                <a href="#" class="menu-item disabled-link">
                    <i class="fas fa-sliders-h"></i> Configurations
                </a>
            </div>
        </div>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-power-off"></i> Secure Logout
                </button>
            </form>
        </div>
    </div>
    <div class="main-content">
        <div class="top-navbar">
            <div style="color: var(--primary); font-weight: 600;">
                <i class="far fa-calendar-alt"></i> {{ date('D, d M Y') }}
            </div>
            <div class="user-profile">
                <div style="text-align: right;">
                    <div style="font-weight: 700; font-size: 15px;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 11px; color: var(--text-gray); text-transform: uppercase;">System Administrator</div>
                </div>
                <div class="avatar-box">
                    @php
                        $nameParts = explode(' ', Auth::user()->name);
                        $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                    @endphp
                    {{ $initials }}
                    <div class="active-dot"></div>
                </div>
            </div>
        </div>
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1 style="margin:0; font-size: 26px;">Welcome back, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                <p style="margin:5px 0 0; color: var(--text-gray);">Terminal status: Online and monitoring inventory.</p>
            </div>
            <i class="fas fa-rocket" style="font-size: 50px; color: #f1f5f9;"></i>
        </div>
        <div class="content-body">
            <div class="stats-grid">
                <div class="stat-card">
                    <div>
                        <h3 style="margin:0; font-size: 14px; color: var(--text-gray);">Total Active Sellers</h3>
                        <p style="font-size:28px; font-weight:700; margin:5px 0 0;">{{ $sellerCount }}</p>
                    </div>
                    <div class="stat-icon bg-blue"><i class="fas fa-users-cog"></i></div>
                </div>
                <div class="stat-card">
                    <div>
                        <h3 style="margin:0; font-size: 14px; color: var(--text-gray);">Registered Brands</h3>
                        <p style="font-size:28px; font-weight:700; margin:5px 0 0;">{{ $brandCount }}</p>
                    </div>
                    <div class="stat-icon bg-green"><i class="fas fa-store"></i></div>
                </div>
                <div class="stat-card">
                    <div>
                        <h3 style="margin:0; font-size: 14px; color: var(--text-gray);">Global Inventory</h3>
                        <p style="font-size:28px; font-weight:700; margin:5px 0 0;">{{ number_format($inventoryCount) }}</p>
                    </div>
                    <div class="stat-icon bg-orange"><i class="fas fa-pallet"></i></div>
                </div>
            </div>
            <div style="margin-top: 30px; background: var(--white); padding: 30px; border-radius: 20px; border-left: 5px solid var(--primary);">
                <h3 style="margin-top:0;">Operation Summary</h3>
                <p style="color: var(--text-gray); line-height: 1.8; font-size: 16px;">
                    Greetings <strong>{{ Auth::user()->name }}</strong>, this is your central command for <strong>Pro-Stock-Manager</strong>. 
                    Manage your merchant network via the <span class="highlight-pin">Seller Management</span> tab and oversee 
                    stock fluctuations through the analytics module.
                </p>
            </div>
        </div>
    </div>
</body>
</html>