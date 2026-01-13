@extends('layouts.admin_master')

@section('title', 'Manage Sellers')

@section('content')
<style>
    /* Basic Layout & Modal */
    #sellerModal * { box-sizing: border-box; }
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(8px); }
    .modal-container { background: white; width: 850px; border-radius: 28px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); position: relative; overflow: hidden; }
    .modal-header { padding: 20px 35px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .close-modal { font-size: 24px; color: #94a3b8; cursor: pointer; transition: 0.3s; }
    .close-modal:hover { color: #ef4444; transform: rotate(90deg); }
    
    /* Stepper UI */
    .stepper-wrapper { display: flex; justify-content: space-between; padding: 30px 100px; position: relative; background: #f8fafc; }
    .stepper-line { position: absolute; top: 50%; left: 100px; right: 100px; height: 4px; background: #e2e8f0; z-index: 1; transform: translateY(-50%); }
    .stepper-progress { position: absolute; top: 50%; left: 100px; width: 0%; max-width: calc(100% - 200px); height: 4px; background: var(--primary); z-index: 1; transform: translateY(-50%); transition: 0.5s ease; }
    .step-item { width: 50px; height: 50px; border-radius: 50%; background: white; border: 3px solid #e2e8f0; display: flex; align-items: center; justify-content: center; z-index: 2; transition: 0.4s; color: #94a3b8; }
    .step-item.active { border-color: var(--primary); color: var(--primary); box-shadow: 0 0 15px rgba(79, 70, 229, 0.2); }
    .step-item.completed { background: var(--primary); color: white; border-color: var(--primary); }
    
    /* Form & Fields */
    .modal-body { padding: 30px 40px; }
    .modal-step { display: none; }
    .modal-step.active { display: block; animation: slideUp 0.4s ease; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .field-row { display: flex; gap: 20px; margin-bottom: 20px; }
    .field-group { flex: 1; display: flex; flex-direction: column; position: relative; margin-bottom: 15px; }
    .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px; }
    .required-star { color: #ef4444; margin-left: 2px; }
    .input-wrapper { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 12px; font-family: 'Laila'; font-size: 15px; transition: 0.3s; }
    .input-wrapper:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
    
    /* VALIDATION CSS */
    .input-wrapper.is-invalid { border-color: #ef4444 !important; background-color: #fffafb; }
    .invalid-feedback { color: #ef4444; font-size: 12px; margin-top: 5px; display: none; font-weight: 600; }
    .input-wrapper.is-invalid ~ .invalid-feedback { display: block !important; }

    /* UNIFIED BUTTON STYLING */
    .btn-footer { display: flex; justify-content: space-between; margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
    .form-btn { height: 50px; min-width: 170px; border-radius: 12px; font-weight: 600; font-family: 'Laila'; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.3s ease; border: none; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-2px); }
    .btn-back { background: #f1f5f9; color: #475569; border: 2px solid #e2e8f0; }
    .btn-back:hover { background: #e2e8f0; color: #1e293b; transform: translateX(-3px); }
    .btn-success { background: var(--success); color: white; min-width: 230px; }
    .btn-success:hover { opacity: 0.9; transform: scale(1.02); }

    /* Action Circle Buttons */
    .action-btn-circle { width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0; background: white; transition: 0.3s; cursor: pointer; }
    .action-btn-circle:hover { transform: scale(1.1); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

    /* Eye Icons & Skills */
    .password-container { position: relative; width: 100%; display: flex; align-items: center; }
    .password-toggle { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; z-index: 10; display: none; padding: 5px; }
    .skill-badge { background: #eef2ff; color: var(--primary); padding: 8px 15px; border-radius: 10px; margin: 5px; display: inline-flex; align-items: center; font-weight: 600; border: 1px solid #c7d2fe; font-size: 13px; font-family: 'Laila'; }

    /* Custom Status Badges */
    .status-pill { padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 800; display: inline-block; letter-spacing: 0.5px; border: 1px solid; }
    .status-active { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }
    .status-deactive { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
</style>

<div style="background: var(--white); padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h2 style="margin:0; font-weight: 700; color: #1e293b;">Seller Management</h2>
            <p style="margin:0; color: #94a3b8; font-size: 13px;">Manage all authorized sellers within Anukramanika</p>
        </div>
        <button type="button" onclick="openSellerModal()" style="background: var(--primary); color: white; padding: 12px 24px; border-radius: 12px; border: none; cursor: pointer; font-weight: 600; font-family: 'Laila'; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-user-plus"></i> Add New Seller
        </button>
    </div>
    
    <table id="sellerDataTable" class="table table-hover" style="width: 100%; border-radius: 12px; overflow: hidden; border: none;">
        <thead style="background-color: #f8fafc;">
            <tr style="text-align: left; color: #64748b; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 700;">
                <th style="padding: 18px 15px;">Seller Name</th>
                <th style="padding: 18px 15px;">Email</th>
                <th style="padding: 18px 15px;">Mobile No</th>
                <th style="padding: 18px 15px;">Location</th>
                <th style="padding: 18px 15px;">Expertise (Skills)</th>
                <th style="padding: 18px 15px;" class="text-center">Status</th>
                <th style="padding: 18px 15px;" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<div class="modal-overlay" id="sellerModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 style="margin:0;">Create Seller Account</h3>
            <i class="fas fa-times close-modal" onclick="closeSellerModal()"></i>
        </div>
        <div class="stepper-wrapper">
            <div class="stepper-line"></div>
            <div class="stepper-progress" id="progressLine"></div>
            <div class="step-item active" id="s1"><i class="fas fa-id-card"></i></div>
            <div class="step-item" id="s2"><i class="fas fa-map-marked-alt"></i></div>
            <div class="step-item" id="s3"><i class="fas fa-shield-alt"></i></div>
        </div>
        <div class="modal-body">
            <form id="multiStepForm" novalidate autocomplete="off">
                @csrf
                <div class="modal-step active" id="step1">
                    <div class="field-row">
                        <div class="field-group">
                            <label class="form-label">Full Name <span class="required-star">*</span></label>
                            <input type="text" name="full_name" class="input-wrapper validate-me letters-only" placeholder="e.g. Nikhil Kothe" minlength="3" required>
                            <div class="invalid-feedback">At least 3 letters required.</div>
                        </div>
                        <div class="field-group">
                            <label class="form-label">Mobile No <span class="required-star">*</span></label>
                            <input type="text" name="mobile_no" class="input-wrapper validate-me digits-only" placeholder="e.g. 98XXXXXXXX" maxlength="10" pattern="^[789]\d{9}$" required>
                            <div class="invalid-feedback">Must be 10 digits starting with 7, 8, or 9.</div>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="form-label">Email Address <span class="required-star">*</span></label>
                        <input type="email" name="email" class="input-wrapper validate-me" placeholder="e.g. nikhil@example.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
                        <div class="invalid-feedback">Enter valid email (e.g. .com, .in).</div>
                    </div>
                    <div class="btn-footer">
                        <div></div>
                        <button type="button" onclick="validateAndNext(2)" class="form-btn btn-primary">Next Step <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                <div class="modal-step" id="step2">
                    <div class="field-row">
                        <div class="field-group">
                            <label class="form-label">Country <span class="required-star">*</span></label>
                            <input type="text" name="country" class="input-wrapper validate-me letters-only" placeholder="e.g. India" minlength="2" required>
                            <div class="invalid-feedback">Country is required.</div>
                        </div>
                        <div class="field-group">
                            <label class="form-label">State <span class="required-star">*</span></label>
                            <input type="text" name="state" class="input-wrapper validate-me letters-only" placeholder="e.g. Maharashtra" minlength="2" required>
                            <div class="invalid-feedback">State is required.</div>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="form-label">Skills <span class="required-star">*</span></label>
                        <div style="display:flex; gap:10px;">
                            <input type="text" id="skillInp" class="input-wrapper" placeholder="e.g. Quants, English">
                            <button type="button" onclick="addNewSkill()" style="background:var(--success); color:white; border:none; padding:0 20px; border-radius:12px; cursor:pointer;"><i class="fas fa-plus"></i></button>
                        </div>
                        <div id="skillsContainer" style="margin-top:15px; min-height:60px; border:2px dashed #e2e8f0; padding:10px; border-radius:15px; background: #fbfcfe;"></div>
                        <div id="skillError" class="invalid-feedback">At least one skill is required.</div>
                    </div>
                    <div class="btn-footer">
                        <button type="button" onclick="goToStep(1)" class="form-btn btn-back"><i class="fas fa-chevron-left"></i> Back</button>
                        <button type="button" onclick="validateAndNext(3)" class="form-btn btn-primary">Next Step <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                <div class="modal-step" id="step3">
                    <div class="field-group">
                        <label class="form-label">Password <span class="required-star">*</span></label>
                        <div class="password-container">
                            <input type="password" name="password" id="pass" class="input-wrapper validate-me" placeholder="••••••••" minlength="6" required>
                            <span class="password-toggle" id="eye-pass" onclick="togglePass('pass', this)"><i class="fas fa-eye-slash"></i></span>
                        </div>
                        <div class="invalid-feedback">Minimum 6 characters required.</div>
                    </div>
                    <div class="field-group">
                        <label class="form-label">Confirm Password <span class="required-star">*</span></label>
                        <div class="password-container">
                            <input type="password" name="password_confirmation" id="cpass" class="input-wrapper validate-me" placeholder="••••••••" required>
                            <span class="password-toggle" id="eye-cpass" onclick="togglePass('cpass', this)"><i class="fas fa-eye-slash"></i></span>
                        </div>
                        <div class="invalid-feedback" id="matchError">Passwords must match.</div>
                    </div>
                    <div class="btn-footer">
                        <button type="button" onclick="goToStep(2)" class="form-btn btn-back"><i class="fas fa-chevron-left"></i> Back</button>
                        <button type="submit" id="submitBtn" class="form-btn btn-success">Complete Registration <i class="fas fa-check-double"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
    let skillsList = [];
    let sellerTable;

    $(document).ready(function() {
        initializeSellerTable();
        
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    });

    function initializeSellerTable() {
        if ($.fn.DataTable.isDataTable('#sellerDataTable')) {
            $('#sellerDataTable').DataTable().destroy();
        }

        sellerTable = $('#sellerDataTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: { url: "{{ route('seller.getAllSellers') }}", type: "GET" },
            columns: [
                { 
                    data: 'name', 
                    className: 'fw-bold align-middle ps-3',
                    render: (data) => `<span style="color: #1e293b; font-size: 14px;">${data}</span>`
                },
                { 
                    data: 'email', 
                    className: 'align-middle',
                    render: (data) => `<span class="text-muted" style="font-size: 13px;">${data}</span>`
                },
                { 
                    data: 'mobile_no', 
                    className: 'align-middle fw-bold',
                    render: (data) => `<span style="color: #475569; font-size: 13px;">${data}</span>`
                },
                { 
                    data: null, 
                    className: 'align-middle text-secondary',
                    render: (data) => `<span style="font-size: 13px;">${data.state}, ${data.country}</span>`
                },
                { 
                    data: 'skills', 
                    className: 'align-middle',
                    render: function(data) {
                        try {
                            let skills = (typeof data === 'string') ? JSON.parse(data) : data;
                            if (Array.isArray(skills)) {
                                return skills.map(skill => `<span class="badge" style="background: #f0f4ff; color: #4f46e5; border: 1px solid #e0e7ff; font-size: 10px; margin: 2px; padding: 5px 10px; border-radius: 6px;">${skill.toUpperCase()}</span>`).join('');
                            }
                            return `<span class="text-muted small">---</span>`;
                        } catch(e) { return `<span class="text-muted small">---</span>`; }
                    }
                },
                { 
                    data: 'status',
                    className: 'text-center align-middle',
                    render: function(data) {
                        let isActive = (data == '0');
                        return `<span class="status-pill ${isActive ? 'status-active' : 'status-deactive'}">
                                    ${isActive ? '● ACTIVE' : '● DEACTIVATED'}
                                </span>`;
                    }
                },
                { 
                    data: null,
                    className: 'text-center align-middle',
                    render: function(data) {
                        let isActive = (data.status == '0');
                        return `
                            <button class="action-btn-circle" onclick="confirmStatusChange(${data.id}, '${data.status}')" title="${isActive ? 'Deactivate' : 'Activate'}">
                                <i class="fas ${isActive ? 'fa-user-slash text-danger' : 'fa-user-check text-success'}"></i>
                            </button>`;
                    }
                }
            ],
            dom: '<"d-flex justify-content-between align-items-center mb-4"f>rt<"d-flex justify-content-between align-items-center mt-4"ip>',
            language: {
                search: "",
                searchPlaceholder: "Search Registry...",
                paginate: {
                    previous: '<i class="fas fa-chevron-left"></i>',
                    next: '<i class="fas fa-chevron-right"></i>'
                }
            }
        });
    }

    function openSellerModal() { 
        $('#multiStepForm')[0].reset(); 
        $('.input-wrapper').removeClass('is-invalid');
        $('.invalid-feedback').hide();
        $('.password-toggle').hide();
        skillsList = [];
        renderSkills();
        goToStep(1);
        $('#sellerModal').css('display', 'flex'); 
    }
    
    function closeSellerModal() { $('#sellerModal').hide(); }

    function togglePass(id, el) {
        let input = document.getElementById(id);
        let icon = $(el).find('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            input.type = 'password';
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        }
    }

    $(document).on('keypress', '.letters-only', function(e) {
        let charCode = (e.which) ? e.which : e.keyCode;
        if ((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && charCode != 32) return false;
    });

    $(document).on('input', '.digits-only', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $(document).on('input', '.validate-me', function() {
        if(this.checkValidity()) $(this).removeClass('is-invalid');
        else $(this).addClass('is-invalid');
        
        if($(this).attr('type') === 'password' || $(this).attr('id') === 'pass' || $(this).attr('id') === 'cpass') {
            let eyeId = '#eye-' + $(this).attr('id');
            $(this).val().length > 0 ? $(eyeId).show() : $(eyeId).hide();
            checkMatch();
        }
    });

    function checkMatch() {
        let p = $('#pass').val(), c = $('#cpass').val();
        if(c.length > 0 && p !== c) {
            $('#cpass').addClass('is-invalid');
            $('#matchError').show();
            return false;
        } else {
            if(c.length > 0) { $('#cpass').removeClass('is-invalid'); $('#matchError').hide(); }
            return true;
        }
    }

    function validateAndNext(step) {
        let currentStepId = '#step' + (step - 1);
        let valid = true;
        $(currentStepId + ' .validate-me').each(function() {
            if(!this.checkValidity()) { $(this).addClass('is-invalid'); valid = false; }
        });
        if(step === 3 && skillsList.length === 0) { $('#skillError').show(); valid = false; }
        if(valid) goToStep(step);
    }

    function goToStep(step) {
        $('.modal-step').removeClass('active');
        $(`#step${step}`).addClass('active');
        let progress = (step === 1) ? '0%' : (step === 2) ? '50%' : '100%';
        $('#progressLine').css('width', progress);
        $('.step-item').each((i, item) => {
            if (i + 1 < step) item.className = 'step-item completed';
            else if (i + 1 === step) item.className = 'step-item active';
            else item.className = 'step-item';
        });
    }

    function addNewSkill() {
        let inp = $('#skillInp'), val = inp.val().trim();
        if(val && !skillsList.includes(val)) {
            skillsList.push(val);
            renderSkills();
            inp.val('');
            $('#skillError').hide();
        }
    }

    function renderSkills() {
        let html = skillsList.map(s => `
            <span class="skill-badge">${s} <i class="fas fa-times-circle" style="cursor:pointer;margin-left:8px;color:#ef4444" onclick="removeSkill('${s}')"></i></span>
        `).join('');
        $('#skillsContainer').html(html);
    }

    function removeSkill(skill) {
        skillsList = skillsList.filter(s => s !== skill);
        renderSkills();
    }

    function confirmStatusChange(id, currentStatus) {
        let isActivating = (currentStatus == '1');
        Swal.fire({
            title: isActivating ? 'Activate Account?' : 'Deactivate Account?',
            text: "Are you sure you want to change this seller's status?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: isActivating ? '#10b981' : '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: isActivating ? 'Yes, Activate' : 'Yes, Deactivate',
            borderRadius: '15px'
        }).then((result) => {
            if (result.isConfirmed) {
                executeToggle(id, isActivating ? '0' : '1');
            }
        });
    }

    function executeToggle(id, newStatus) {
        $.post("{{ route('seller.toggleStatus') }}", { id: id, status: newStatus })
        .done(function(response) {
            if(response.status == 'success') {
                Swal.fire({ icon: 'success', title: 'AUTHORIZED', text: 'Status updated successfully', timer: 1500, showConfirmButton: false });
                sellerTable.ajax.reload(null, false);
            }
        });
    }

    $('#multiStepForm').on('submit', function(e) {
        e.preventDefault();
        let valid = true;
        $('#step3 .validate-me').each(function() {
            if(!this.checkValidity()) { $(this).addClass('is-invalid'); valid = false; }
        });
        if(!checkMatch()) valid = false;
        if(skillsList.length === 0) { $('#skillError').show(); valid = false; }
        if(!valid) return;

        let btn = $('#submitBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> AUTHORIZING...');

        $.ajax({
            url: "{{ route('seller.store') }}",
            type: "POST",
            data: $(this).serialize() + "&skills=" + JSON.stringify(skillsList),
            success: function(response) {
                if(response.status == 'success') {
                    closeSellerModal();
                    Swal.fire({ icon: 'success', title: 'AUTHORIZED', text: response.message });
                    sellerTable.ajax.reload();
                }
            },
            error: function() { Swal.fire({ icon: 'error', title: 'DENIED', text: 'System Error' }); },
            complete: function() { btn.prop('disabled', false).html('Complete Registration <i class="fas fa-check-double"></i>'); }
        });
    });
</script>
@endsection