@extends('layouts.admin_master')

@section('title', 'System Logs')

@section('content')
<div style="background: var(--white); padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="margin:0; font-weight: 700; color: #1e293b;">System Logs</h2>
            <p style="margin:0; color: #94a3b8; font-size: 13px;">Review system-wide activities and security logs</p>
        </div>
        <button type="button" onclick="confirmClearLogs()" style="background: #ef444410; color: #ef4444; border: 1px solid #ef444420; padding: 12px 25px; border-radius: 12px; cursor: pointer; font-weight: 600; font-family: 'Laila'; display: flex; align-items: center; gap: 8px; transition: 0.3s;" onmouseover="this.style.background='#ef4444'; this.style.color='white'" onmouseout="this.style.background='#ef444410'; this.style.color='#ef4444'">
            <i class="fas fa-trash-alt"></i> Clear Data Vault
        </button>
    </div>

    <!-- Refined Premium Filter Bar -->
    <div style="margin-bottom: 35px; padding: 20px; background: #f8fafc; border-radius: 20px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; gap: 20px;">
        <div style="flex: 1; position: relative; max-width: 500px;">
            <i class="fas fa-search" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 16px;"></i>
            <input type="text" id="logSearch" style="width: 100%; height: 50px; padding: 0 20px 0 52px; border-radius: 14px; border: 1px solid #e2e8f0; font-family: 'Laila'; font-size: 14px; transition: all 0.3s ease; background: white; outline: none; box-shadow: 0 2px 4px rgba(0,0,0,0.02);" placeholder="Search user account or details..." onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(67, 56, 202, 0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.02)'">
        </div>
        <div style="width: 250px;">
            <div style="position: relative;">
                <i class="fas fa-filter" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 14px; pointer-events: none;"></i>
                <select id="actionFilter" style="width: 100%; height: 50px; padding: 0 15px 0 40px; border-radius: 14px; border: 1px solid #e2e8f0; font-family: 'Laila'; background: white; font-weight: 600; color: #1e293b; cursor: pointer; font-size: 14px; appearance: none; outline: none; transition: 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e2e8f0'">
                    <option value="">▼ FILTER RECORDS</option>
                    <option value="LOGIN">LOGIN EVENTS</option>
                    <option value="CREATE">CREATE LOGS</option>
                    <option value="UPDATE">UPDATE LOGS</option>
                    <option value="DELETE">DELETE LOGS</option>
                    <option value="OPTIMIZE">OPTIMIZE LOGS</option>
                </select>
                <i class="fas fa-chevron-down" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; font-size: 12px;"></i>
            </div>
        </div>
    </div>

    @if($logs->isEmpty())
    <div id="emptyLogs" style="text-align: center; padding: 80px 20px; background: #fbfcfe; border-radius: 24px; border: 2px dashed #e2e8f0;">
        <div style="background: #f1f5f9; width: 100px; height: 100px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; color: #94a3b8; font-size: 35px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(0,0,0,0.02);">
            <i class="fas fa-database"></i>
        </div>
        <h3 style="margin: 0; color: #1e293b; font-size: 20px;">Terminal Index Empty</h3>
        <p style="color: #94a3b8; max-width: 400px; margin: 10px auto 0; line-height: 1.6;">No activity has been recorded in the system vaults yet. New entries will appear here automatically.</p>
    </div>
    @else
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="text-align: left; background: #f8fafc;">
                    <th style="padding: 15px 20px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; border-radius: 12px 0 0 0; font-weight: 700;">Timestamp</th>
                    <th style="padding: 15px 20px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">User Account</th>
                    <th style="padding: 15px 20px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Operation</th>
                    <th style="padding: 15px 20px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Event Details</th>
                    <th style="padding: 15px 20px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; text-align: right; border-radius: 0 12px 0 0; font-weight: 700;">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr class="log-row" style="transition: all 0.2s ease; border-bottom: 1px solid #f1f5f9; cursor: default;">
                    <td style="padding: 18px 20px; font-size: 13px; color: #64748b;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <i class="far fa-clock" style="color: var(--primary); opacity: 0.6;"></i> 
                            {{ date('d M Y, H:i', strtotime($log->created_at)) }}
                        </span>
                    </td>
                    <td style="padding: 18px 20px; font-weight: 600; color: #1e293b; font-size: 14px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 12px;">
                                <i class="fas fa-user"></i>
                            </div>
                            {{ $log->user_name ?? 'System Process' }}
                        </div>
                    </td>
                    <td style="padding: 18px 20px;">
                        @php
                            $color = match(strtoupper($log->action)) {
                                'CREATE' => ['#dcfce7', '#166534'],
                                'DELETE' => ['#fee2e2', '#991b1b'],
                                'UPDATE' => ['#fef9c3', '#854d0e'],
                                'LOGIN' => ['#e0e7ff', '#3730a3'],
                                default => ['#f1f5f9', '#475569'],
                            };
                        @endphp
                        <span style="background: {{ $color[0] }}; color: {{ $color[1] }}; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td style="padding: 18px 20px; font-size: 13px; color: #475569; max-width: 400px; line-height: 1.5;">
                        {{ $log->details }}
                    </td>
                    <td style="padding: 18px 20px; text-align: right; font-family: 'JetBrains Mono', monospace; color: #94a3b8; font-size: 12px;">
                        {{ $log->ip_address ?? '::1' }}
                    </td>
                </tr>
                @endforeach
                
                <!-- No Results Row -->
                <tr id="noResultsRow" style="display: none;">
                    <td colspan="5" style="padding: 60px 20px; text-align: center;">
                        <div style="background: #f8fafc; width: 60px; height: 60px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; color: #cbd5e1; font-size: 24px; margin-bottom: 15px;">
                            <i class="fas fa-search"></i>
                        </div>
                        <h4 style="margin: 0; color: #64748b; font-size: 16px;">No Data Available</h4>
                        <p style="margin: 5px 0 0; color: #94a3b8; font-size: 13px;">No matching records were found for your current search or filter.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <style>
        .log-row:hover {
            background: #f8fafc !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
        #actionFilter:focus {
            box-shadow: 0 0 0 4px rgba(67, 56, 202, 0.1);
        }
    </style>

    <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection

@section('extra_js')
<script>
    function confirmClearLogs() {
        Swal.fire({
            title: 'Purge System Logs?',
            text: 'This action is irreversible and will erase all audit trails.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Purge Vault',
            cancelButtonText: 'Cancel',
            background: '#ffffff',
            borderRadius: '20px'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('admin.logs.clear') }}")
                .done(function(res) {
                    Swal.fire({
                        title: 'Purged!',
                        text: 'All audit logs have been cleared successfully.',
                        icon: 'success',
                        confirmButtonColor: 'var(--primary)',
                        borderRadius: '20px'
                    }).then(() => location.reload());
                });
            }
        });
    }

    function filterLogs() {
        let searchVal = $('#logSearch').val().toLowerCase();
        let actionVal = $('#actionFilter').val().toLowerCase();
        let visibleCount = 0;
        
        $('tbody tr.log-row').each(function() {
            let rowText = $(this).text().toLowerCase();
            let rowAction = $(this).find('td:nth-child(3)').text().trim().toUpperCase();
            
            let matchesSearch = rowText.includes(searchVal);
            let matchesAction = actionVal === "" || rowAction.includes(actionVal);
            
            if (matchesSearch && matchesAction) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });

        if(visibleCount === 0) {
            $('#noResultsRow').show();
        } else {
            $('#noResultsRow').hide();
        }
    }

    $('#logSearch').on('keyup', filterLogs);
    $('#actionFilter').on('change', filterLogs);
</script>
@endsection
