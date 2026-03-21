@extends('layouts.customer_master')

@section('title', 'Account Settings')

@section('content')
<div style="display: flex; gap: 40px; align-items: flex-start; animation: fadeIn 0.4s ease-out; flex-wrap: wrap;">
    
    <!-- Settings Navigator -->
    <div style="width: 300px; min-width: 300px; background: white; border-radius: 24px; padding: 24px; border: 1px solid #F3F4F6; box-shadow: var(--card-shadow); position: sticky; top: 40px;">
        <div style="margin-bottom: 24px;">
            <h3 style="margin: 0 0 16px; font-size: 14px; color: #9CA3AF; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Settings Hub</h3>
            <p style="margin:0; font-size: 13px; color: #6B7280; font-weight: 500;">Manage your identity and security.</p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 8px;">
            <button class="menu-btn active" onclick="scrollToSection('profile-section')">
                <i class="fas fa-user-circle"></i> <span>Profile Details</span>
            </button>
            <button class="menu-btn" onclick="scrollToSection('security-section')">
                <i class="fas fa-key"></i> <span>Security Settings</span>
            </button>
            <button class="menu-btn" onclick="scrollToSection('privacy-section')">
                <i class="fas fa-shield-alt"></i> <span>Account Protection</span>
            </button>
        </div>

        <div style="margin-top: 40px; padding: 20px; background: #F9FAFB; border-radius: 16px; border: 1px solid #F3F4F6;">
            <div style="font-size: 11px; font-weight: 800; color: #9CA3AF; text-transform: uppercase; margin-bottom: 8px;">Trust Score</div>
            <div style="display: flex; align-items: center; gap: 8px; color: #059669; font-weight: 700; font-size: 14px;">
                <i class="fas fa-certificate"></i> Verified Profile
            </div>
        </div>
    </div>

    <!-- Main Settings Panels -->
    <div style="flex: 1; min-width: 350px;">
        
        <!-- Header Banner matching Dashboard style -->
        <div style="margin-bottom: 40px; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); padding: 45px; border-radius: 32px; color: white; position: relative; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2); border: 1px solid rgba(255,255,255,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <div>
                    <h1 style="margin:0; font-size: 32px; font-weight: 800; letter-spacing: -1.5px; margin-bottom: 10px;">Account Settings <span style="color: #818cf8;">.</span></h1>
                    <p style="margin:0; color: #94a3b8; font-size: 15px; font-weight: 500;">Update your personal info and keep your account secure in the ProStock vault.</p>
                </div>
                <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 20px; display: flex; align-items: center; justify-content: center; transform: rotate(-5deg); border: 1px solid rgba(255,255,255,0.05);">
                    <i class="fas fa-cog" style="font-size: 30px; color: #818cf8;"></i>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div style="background: #ECFDF5; border: 1px solid #D1FAE5; color: #059669; padding: 18px 24px; border-radius: 16px; margin-bottom: 30px; font-weight: 700; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <i class="fas fa-check-double"></i> {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('customer.profile.update') }}" method="POST">
            @csrf
            
            <!-- Section 1: Profile Details -->
            <div id="profile-section" class="settings-card">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
                    <i class="fas fa-user-edit" style="color: #4F46E5; font-size: 18px;"></i>
                    <h2 style="margin:0; font-size: 16px; font-weight: 800; color: #111827; text-transform: uppercase; letter-spacing: 1px;">Profile Details</h2>
                </div>
                <!-- Fixed Responsive Grid to prevent overlap -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px 25px;">
                    <div class="field-item">
                        <label>Your Full Name</label>
                        <input type="text" name="name" value="{{ $customer->name }}" required>
                    </div>
                    <div class="field-item">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile_no" value="{{ $customer->mobile_no }}" required>
                    </div>
                    <div class="field-item locked">
                        <label>Email Address (Cannot change)</label>
                        <input type="email" value="{{ $customer->email }}" disabled>
                    </div>
                    <div class="field-item locked">
                        <label>Your Location</label>
                        <input type="text" value="{{ $customer->state }}, {{ $customer->country }}" disabled>
                    </div>
                </div>
            </div>

            <!-- Section 2: Security Settings -->
            <div id="security-section" class="settings-card" style="margin-top: 30px;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
                    <i class="fas fa-lock" style="color: #F59E0B; font-size: 18px;"></i>
                    <h2 style="margin:0; font-size: 16px; font-weight: 800; color: #111827; text-transform: uppercase; letter-spacing: 1px;">Security Settings</h2>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px 25px; margin-bottom: 30px;">
                    <div class="field-item">
                        <label>New Password</label>
                        <input type="password" name="password" placeholder="Leave empty to keep same">
                    </div>
                    <div class="field-item">
                        <label>Confirm New Password</label>
                        <input type="password" name="password_confirmation" placeholder="Re-type your password">
                    </div>
                </div>
                <div style="text-align: right;">
                    <button type="submit" class="prime-save-btn">Save All Changes</button>
                </div>
            </div>
        </form>

        <!-- Section 3: Account Protection -->
        <div id="privacy-section" class="settings-card" style="margin-top: 30px; background: #fffcfc; border-color: #fee2e2;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
                <i class="fas fa-exclamation-triangle" style="color: #DC2626; font-size: 18px;"></i>
                <h2 style="margin:0; font-size: 16px; font-weight: 800; color: #991B1B; text-transform: uppercase; letter-spacing: 1px;">Account Protection</h2>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="background: white; border: 1px solid #F3F4F6; padding: 25px; border-radius: 20px; display: flex; justify-content: space-between; align-items: center; gap: 30px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 250px;">
                        <h4 style="margin:0; font-size: 15px; font-weight: 700; color: #111827;">Temporarily Disable Account</h4>
                        <p style="margin:6px 0 0; font-size: 13px; color: #64748b; line-height: 1.5;">Your profile will go offline. You can return by signing in anytime.</p>
                    </div>
                    <button class="action-btn disable-btn" onclick="confirmDeactivate()">Disable Node</button>
                </div>
                
                <div style="background: #FFF5F5; border: 1px solid #FED7D7; padding: 25px; border-radius: 20px; display: flex; justify-content: space-between; align-items: center; gap: 30px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 250px;">
                        <h4 style="margin:0; font-size: 15px; font-weight: 700; color: #991B1B;">Permanently Delete Account</h4>
                        <p style="margin:6px 0 0; font-size: 13px; color: #B91C1C; line-height: 1.5;">This will purge all your data instantly. This action is irreversible.</p>
                    </div>
                    <button class="action-btn delete-btn" onclick="confirmWipe()">Delete Account</button>
                </div>
            </div>
        </div>

        <div style="height: 50px;"></div>
    </div>
</div>

<style>
    * { box-sizing: border-box; }
    
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .menu-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: transparent;
        border: none;
        border-radius: 14px;
        cursor: pointer;
        transition: 0.2s;
        text-align: left;
        color: #4B5563;
        font-weight: 500;
        font-size: 13px;
        font-family: inherit;
    }
    .menu-btn i { width: 16px; font-size: 14px; color: #9CA3AF; }
    .menu-btn:hover { background: #F9FAFB; color: #111827; }
    .menu-btn.active { background: #EEF2FF; color: #4F46E5; font-weight: 700; }
    .menu-btn.active i { color: #4F46E5; }

    .settings-card { background: white; border-radius: 20px; padding: 35px; border: 1px solid #F3F4F6; box-shadow: var(--card-shadow); overflow: hidden; }

    .field-item { display: flex; flex-direction: column; gap: 10px; width: 100%; }
    .field-item label { font-size: 12px; font-weight: 700; color: #4B5563; margin-bottom: 2px; }
    .field-item input { 
        width: 100%; 
        background: #F9FAFB; border: 1px solid #E5E7EB; padding: 14px 18px; border-radius: 14px; font-size: 14px; font-weight: 600; color: #111827; transition: 0.2s; font-family: inherit;
    }
    .field-item input:focus { border-color: #4F46E5; background: white; outline: none; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.05); }
    .field-item.locked input { opacity: 0.6; cursor: not-allowed; background: #F3F4F6; }

    .prime-save-btn {
        background: #111827; color: white; border: none; padding: 16px 40px; border-radius: 14px; font-weight: 800; font-size: 14px; cursor: pointer; transition: 0.2s;
    }
    .prime-save-btn:hover { background: #4F46E5; transform: translateY(-2px); }

    .action-btn { 
        padding: 14px 30px; 
        min-width: 160px;
        border-radius: 14px; 
        font-weight: 800; 
        font-size: 13px; 
        cursor: pointer; 
        transition: 0.3s; 
        letter-spacing: 0.5px;
        font-family: inherit;
    }
    
    .disable-btn {
        background: #020617;
        color: white; 
        border: none;
    }
    .disable-btn:hover { background: #1e1b4b; transform: translateY(-2px); }

    .delete-btn {
        background: #DC2626;
        color: white; 
        border: none;
    }
    .delete-btn:hover { background: #991B1B; transform: translateY(-2px); }
</style>

@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function scrollToSection(id) {
        const el = document.getElementById(id);
        if(el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        $('.menu-btn').removeClass('active');
        $(`button[onclick="scrollToSection('${id}')"]`).addClass('active');
    }
    function confirmDeactivate() {
        Swal.fire({ title: 'Disable Account?', text: "Return anytime by logging in again.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#020617', confirmButtonText: 'Yes, Disable' });
    }
    function confirmWipe() {
        Swal.fire({ title: 'Delete Permanently?', text: "Warning: All data will be erased forever.", icon: 'error', showCancelButton: true, confirmButtonColor: '#DC2626', confirmButtonText: 'Delete Forever' });
    }
</script>
@endsection
@endsection
