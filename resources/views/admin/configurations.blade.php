@extends('layouts.admin_master')

@section('title', 'System Configurations')

@section('content')
<div style="background: var(--white); padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="margin:0; font-weight: 700; color: #1e293b;">Global Configurations</h2>
            <p style="margin:0; color: #94a3b8; font-size: 13px;">Manage application-wide parameters and preferences</p>
        </div>
        <div style="background: #e0e7ff; color: #4338ca; padding: 8px 15px; border-radius: 10px; font-size: 12px; font-weight: 700;">
            <i class="fas fa-shield-alt"></i> SYSTEM CORE ACTIVE
        </div>
    </div>

    @php
        $siteTitle = $configs->where('key', 'site_title')->first()->value ?? 'Pro Stock Manager';
        $adminEmail = $configs->where('key', 'admin_email')->first()->value ?? 'admin@prostock.com';
        $isMaintenance = $configs->where('key', 'system_maintenance')->first()->value ?? '0';
        $maxSize = $configs->where('key', 'max_upload_size')->first()->value ?? '2048';
    @endphp

    <!-- Configuration Panels -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
        <!-- Common Settings -->
        <div style="background: #f8fafc; padding: 30px; border-radius: 24px; border: 1px solid #e2e8f0;">
            <h3 style="margin: 0 0 25px; font-size: 18px; color: #1e293b; border-bottom: 2px solid #e0e7ff; padding-bottom: 10px; display: inline-block;">General Identity</h3>
            <form id="identityForm">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #475569;">Application Version</label>
                    <input type="text" value="v1.0.4 - Release (Alpha)" readonly style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; background: #f1f5f9; color: #94a3b8; font-family: 'Laila'; cursor: not-allowed;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #475569;">System Site Title</label>
                    <input type="text" name="site_title" value="{{ $siteTitle }}" style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-family: 'Laila'; transition: 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#cbd5e1'">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #475569;">Primary Admin Contact</label>
                    <input type="email" name="admin_email" value="{{ $adminEmail }}" style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-family: 'Laila'; transition: 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#cbd5e1'">
                </div>
                
                <button type="submit" style="background: var(--primary); color: white; border: none; padding: 15px 30px; border-radius: 12px; font-weight: 600; font-family: 'Laila'; cursor: pointer; transition: 0.3s; width: 100%;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i> Save General Identity
                </button>
            </form>
        </div>

        <!-- Security Settings -->
        <div style="background: white; padding: 25px; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
            <h3 style="margin: 0 0 25px; font-size: 18px; color: #1e293b; border-bottom: 2px solid #fee2e2; padding-bottom: 10px; display: inline-block;">Security & Storage</h3>
            <div style="margin-bottom: 25px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <div>
                        <h4 style="margin: 0; font-size: 14px; color: #1e293b;">Maintenance Mode</h4>
                        <p style="margin: 0; font-size: 12px; color: #94a3b8;">Prevent sellers from accessing their dashboards</p>
                    </div>
                    <div onclick="toggleConfig('system_maintenance', this)" style="background: {{ $isMaintenance == '1' ? 'var(--primary)' : '#e2e8f0' }}; width: 45px; height: 24px; border-radius: 12px; position: relative; cursor: pointer; transition: 0.3s;">
                        <div style="background: white; width: 18px; height: 18px; border-radius: 50%; position: absolute; {{ $isMaintenance == '1' ? 'right: 3px;' : 'left: 3px;' }} top: 3px; transition: 0.3s;"></div>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <div>
                        <h4 style="margin: 0; font-size: 14px; color: #1e293b;">Image Quality Restriction</h4>
                        <p style="margin: 0; font-size: 12px; color: #94a3b8;">Limit brand image uploads to {{ $maxSize }}KB per file</p>
                    </div>
                    <div onclick="toggleConfig('max_upload_size', this)" style="background: var(--success); width: 45px; height: 24px; border-radius: 12px; position: relative; cursor: pointer;">
                        <div style="background: white; width: 18px; height: 18px; border-radius: 50%; position: absolute; right: 3px; top: 3px; transition: 0.3s;"></div>
                    </div>
                </div>
            </div>
            
            <div style="background: #fff7ed; padding: 15px; border-radius: 12px; color: #9a3412; font-size: 12px; border-left: 4px solid #f97316;">
                <i class="fas fa-exclamation-triangle" style="margin-right: 5px;"></i>
                Caution: System maintenance mode will revoke all active seller sessions immediately upon activation.
            </div>

            <button type="button" onclick="runOptimization()" style="background: #1e293b; color: white; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 600; font-family: 'Laila'; cursor: pointer; margin-top: 25px; width: 100%; transition: 0.3s;" onmouseover="this.style.background='#334155'" onmouseout="this.style.background='#1e293b'">
                <i class="fas fa-bolt"></i> Optimize Database Performance
            </button>
        </div>
    </div>

    <!-- About Section -->
    <div style="margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 30px; text-align: center;">
        <p style="color: #94a3b8; font-size: 12px;">
            <i class="fas fa-code"></i> Developed by <strong>Nikhil Kothe</strong><br>
            All Rights Reserved © 2026
        </p>
    </div>
</div>
@endsection

@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    function toggleConfig(key, el) {
        let currentBg = $(el).css('background-color');
        // Check if it's currently OFF (greyish)
        let isCurrentlyOff = (currentBg === 'rgb(226, 232, 240)' || currentBg === 'transparent'); 
        let newValue = isCurrentlyOff ? '1' : '0';
        let defaultColor = key === 'system_maintenance' ? '#4f46e5' : '#22c55e'; // blue or green
        
        Swal.fire({
            title: isCurrentlyOff ? 'Enable Feature?' : 'Disable Feature?',
            text: 'System behavior will be updated immediately.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: defaultColor
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('admin.configurations.updateSingle') }}", { key: key, value: newValue })
                .done(function(res) {
                    if(res.status === 'success') {
                        let dot = $(el).find('div');
                        if (isCurrentlyOff) {
                            $(el).css('background-color', defaultColor);
                            dot.css({'left': 'auto', 'right': '3px'});
                        } else {
                            $(el).css('background-color', '#e2e8f0');
                            dot.css({'left': '3px', 'right': 'auto'});
                        }
                        location.reload(); // Reload to apply changes (like site title)
                    }
                });
            }
        });
    }

    $('#identityForm').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({ title: 'Applying changes...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
        
        $.post("{{ route('admin.configurations.update') }}", $(this).serialize())
        .done(function(res) {
            Swal.fire({ icon: 'success', title: 'System Updated', text: 'Identity configurations applied successfully!' })
            .then(() => location.reload());
        })
        .fail(function() {
            Swal.fire('Error', 'Failed to update identity.', 'error');
        });
    });

    function runOptimization() {
        Swal.fire({
            title: 'Analyze & Optimize?',
            text: 'System performance will be enhanced through cache purging.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Execute Optimization'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
                
                $.post("{{ route('admin.optimize') }}")
                .done(function(res) {
                    Swal.fire('Optimized!', res.message, 'success');
                })
                .fail(function() {
                    Swal.fire('Error', 'Optimization sequence failed.', 'error');
                });
            }
        });
    }
</script>
@endsection
