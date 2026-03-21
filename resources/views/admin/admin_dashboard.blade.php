@extends('layouts.admin_master')

@section('title', 'Admin Dashboard')

@section('content')
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 30px;">
    <div class="stat-card" style="background: var(--white); padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h3 style="margin:0; font-size: 14px; color: var(--text-gray); text-transform: uppercase; letter-spacing: 0.5px;">Active Sellers</h3>
            <p style="font-size:32px; font-weight:700; margin:8px 0 0; color: #1e293b;">{{ $sellerCount }}</p>
        </div>
        <div class="stat-icon" style="width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 28px; background: #eef2ff; color: #4338ca;">
            <i class="fas fa-users-cog"></i>
        </div>
    </div>
    <div class="stat-card" style="background: var(--white); padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h3 style="margin:0; font-size: 14px; color: var(--text-gray); text-transform: uppercase; letter-spacing: 0.5px;">Product Brands</h3>
            <p style="font-size:32px; font-weight:700; margin:8px 0 0; color: #1e293b;">{{ $brandCount }}</p>
        </div>
        <div class="stat-icon" style="width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 28px; background: #f0fdf4; color: #166534;">
            <i class="fas fa-store"></i>
        </div>
    </div>
    <div class="stat-card" style="background: var(--white); padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h3 style="margin:0; font-size: 14px; color: var(--text-gray); text-transform: uppercase; letter-spacing: 0.5px;">Master Inventory</h3>
            <p style="font-size:32px; font-weight:700; margin:8px 0 0; color: #1e293b;">{{ number_format($inventoryCount) }}</p>
        </div>
        <div class="stat-icon" style="width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 28px; background: #fff7ed; color: #9a3412;">
            <i class="fas fa-pallet"></i>
        </div>
    </div>
</div>

<div style="background: var(--white); padding: 40px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.02); border-left: 6px solid var(--primary); display: flex; gap: 30px; align-items: center;">
    <div style="background: #eef2ff; width: 80px; height: 80px; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 35px; color: var(--primary);">
        <i class="fas fa-microchip"></i>
    </div>
    <div>
        <h3 style="margin:0; font-size: 22px; color: #1e293b;">System Operations Summary</h3>
        <p style="margin:10px 0 0; color: var(--text-gray); line-height: 1.8; font-size: 16px; max-width: 800px;">
            Greetings <strong>{{ Auth::user()->name }}</strong>, you are currently in the <strong>Pro-Stock-Manager</strong> central command. 
            All terminal functions are operational. You can now manage your authorized merchant network via <strong style="color: var(--primary);">Seller Management</strong> 
            and deep-dive into stock fluctuations through the <strong style="color: var(--primary);">Inventory Analytics</strong> module.
        </p>
    </div>
</div>

<div style="margin-top: 30px; display: grid; grid-template-columns: 1fr 1.5fr; gap: 25px;">
    <div style="background: white; border-radius: 20px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
        <h4 style="margin:0 0 20px; font-size: 16px; color: #1e293b; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-bell text-warning"></i> Recent Alerts
        </h4>
        <div style="padding: 12px; background: #fff9f0; border-radius: 12px; border-left: 4px solid #f59e0b; margin-bottom: 15px;">
            <span style="font-size: 12px; font-weight: 700; color: #92400e;">SYSTEM READY</span><br>
            <span style="font-size: 13px; color: #b45309;">Last backup successful at 03:00 AM</span>
        </div>
        <div style="padding: 12px; background: #f0fdf4; border-radius: 12px; border-left: 4px solid #10b981;">
            <span style="font-size: 12px; font-weight: 700; color: #065f46;">SECURITY SCAN</span><br>
            <span style="font-size: 13px; color: #059669;">All systems scanned. No anomalies found.</span>
        </div>
    </div>
    <div style="background: white; border-radius: 20px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
        <h4 style="margin:0 0 20px; font-size: 16px; color: #1e293b;">Quick Launch Terminal</h4>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="{{ route('admin.analytics') }}" style="flex: 1; min-width: 150px; background: #f8fafc; padding: 20px; border-radius: 15px; text-decoration: none; text-align: center; border: 1px solid #e2e8f0; transition: 0.3s;" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                <i class="fas fa-chart-line" style="font-size: 24px; color: var(--primary); margin-bottom: 10px;"></i><br>
                <span style="font-weight: 600; color: #1e293b; font-size: 14px;">View Analytics</span>
            </a>
            <a href="{{ route('admin.sellers') }}" style="flex: 1; min-width: 150px; background: #f8fafc; padding: 20px; border-radius: 15px; text-decoration: none; text-align: center; border: 1px solid #e2e8f0; transition: 0.3s;" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                <i class="fas fa-user-shield" style="font-size: 24px; color: var(--primary); margin-bottom: 10px;"></i><br>
                <span style="font-weight: 600; color: #1e293b; font-size: 14px;">Manage Access</span>
            </a>
            <a href="{{ route('admin.logs') }}" style="flex: 1; min-width: 150px; background: #f8fafc; padding: 20px; border-radius: 15px; text-decoration: none; text-align: center; border: 1px solid #e2e8f0; transition: 0.3s;" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                <i class="fas fa-terminal" style="font-size: 24px; color: var(--primary); margin-bottom: 10px;"></i><br>
                <span style="font-weight: 600; color: #1e293b; font-size: 14px;">System Audit</span>
            </a>
        </div>
    </div>
</div>
@endsection