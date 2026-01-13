@extends('layouts.seller_master')

@section('title', 'Inventory Manager')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    :root { --primary: #4f46e5; --bg-light: #f8fafc; --danger: #ef4444; }
    .premium-card { background: white; padding: 30px; border-radius: 22px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); margin-bottom: 20px; }
    .req-star { color: var(--danger); font-weight: bold; margin-left: 3px; }
    .invalid-feedback { display: none; color: var(--danger); font-size: 11px; font-weight: 600; margin-top: 5px; }
    .is-invalid { border-color: var(--danger) !important; background-color: #fffafb !important; }

    #productDataTable { border-collapse: separate !important; border-spacing: 0 12px !important; width: 100% !important; border: none !important; }
    #productDataTable thead th { background: var(--bg-light); color: #64748b; font-weight: 700; font-size: 12px; padding: 15px; border: none; text-transform: uppercase; }
    #productDataTable tbody td { background: white; padding: 15px; border: none; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    
    .action-wrapper { display: flex; gap: 8px; justify-content: center; align-items: center; white-space: nowrap; }
    .action-btn { border: none; padding: 10px 14px; border-radius: 10px; cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; justify-content: center; }
    .btn-view { background: #eef2ff; color: var(--primary); }
    .btn-pdf { background: #e0f2fe; color: #0369a1; }
    .btn-delete { background: #fee2e2; color: #ef4444; }

    #productModal, #viewBrandModal { display:none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); overflow-y: auto; backdrop-filter: blur(5px); }
    .modal-container { background: white; width: 98%; max-width: 1200px; margin: 20px auto; padding: 35px; border-radius: 25px; position: relative; }
    .form-group { margin-bottom: 20px; width: 100%; }
    .form-label { font-weight: 700; font-size: 13px; color: #475569; margin-bottom: 8px; display: block; }
    .form-control { width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; box-sizing: border-box; }
    .brand-row { display: grid; grid-template-columns: 1.2fr 0.8fr 1.2fr 1.2fr 40px; gap: 15px; background: #f8fafc; padding: 20px; border-radius: 15px; margin-bottom: 15px; border: 1px solid #e2e8f0; align-items: center; }

    .view-table th { background: #f1f5f9; padding: 12px; font-size: 13px; color: #475569; }
    .view-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
</style>

<div class="premium-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h3 style="font-weight: 800; color: #1e293b; margin: 0;">Inventory Log</h3>
            <p style="color: #94a3b8; margin:0; font-size: 13px;">Manage products and variations for Anukramanika</p>
        </div>
        <button onclick="openProductModal()" style="background: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 12px; font-weight: 700; cursor: pointer;">
            <i class="fas fa-plus"></i> NEW PRODUCT
        </button>
    </div>

    <div class="table-responsive">
        <table id="productDataTable" class="table">
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th width="22%">Product Name</th>
                    <th width="30%">Description</th>
                    <th width="15%" class="text-center">Variations</th>
                    <th width="25%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="productModal">
    <div class="modal-container">
        <span onclick="closeModal()" style="position: absolute; right: 25px; top: 20px; font-size: 28px; cursor: pointer; color: #94a3b8;">&times;</span>
        <h3 style="font-weight: 800; margin-bottom: 25px;">Create New Inventory Entry</h3>

        <form id="masterProductForm" enctype="multipart/form-data" novalidate>
            @csrf
            <div id="productTab">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                    <div class="form-group">
                        <label class="form-label">Product Name <span class="req-star">*</span></label>
                        <input type="text" name="product_name" class="form-control" placeholder="e.g. Smart LED TV" oninput="clearValidation(this)">
                        <div class="invalid-feedback">Enter at least 3 characters.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description <span class="req-star">*</span></label>
                        <input type="text" name="product_description" class="form-control" placeholder="e.g. 4K Ultra HD, Dolby Audio, 55 inch" oninput="clearValidation(this)">
                        <div class="invalid-feedback">Enter at least 10 characters.</div>
                    </div>
                </div>
                <div style="text-align: right; margin-top: 30px;">
                    <button type="button" onclick="goToBrands()" style="background: var(--primary); color: white; padding: 14px 40px; border: none; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Next: Brand Details <i class="fas fa-arrow-right" style="margin-left:8px;"></i>
                    </button>
                </div>
            </div>

            <div id="brandTab" style="display:none;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px; align-items: center;">
                    <h5 style="font-weight: 700; margin: 0;">Add Brand Variations</h5>
                    <button type="button" onclick="addBrandRow()" style="background: #f1f5f9; color: var(--primary); border: 1px dashed var(--primary); padding: 8px 15px; border-radius: 10px; cursor: pointer;">+ ADD ROW</button>
                </div>
                <div id="brandContainer" style="max-height: 400px; overflow-y: auto;">
                    <div class="brand-row">
                        <div class="form-group">
                            <label class="form-label">Brand <span class="req-star">*</span></label>
                            <input type="text" name="brand_name[]" class="form-control" placeholder="e.g. Sony" oninput="clearValidation(this)">
                            <div class="invalid-feedback">Min 3 chars.</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Price <span class="req-star">*</span></label>
                            <input type="number" name="price[]" class="form-control" placeholder="e.g. 55000" onkeypress="return event.charCode >= 49" oninput="clearValidation(this)">
                            <div class="invalid-feedback">Price must be at least ₹10.</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Image <span class="req-star">*</span></label>
                            <input type="file" name="brand_image[]" class="form-control" onchange="clearValidation(this)">
                            <div class="invalid-feedback">Required.</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Specs <span class="req-star">*</span></label>
                            <input type="text" name="detail[]" class="form-control" placeholder="e.g. OLED, 120Hz Refresh Rate" oninput="clearValidation(this)">
                            <div class="invalid-feedback">Required.</div>
                        </div>
                        <div style="text-align: center; color: #cbd5e1;"><i class="fas fa-lock"></i></div>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                    <button type="button" onclick="backToProduct()" style="border:none; background:#f1f5f9; color:#475569; padding: 12px 25px; border-radius:10px; font-weight:700; cursor:pointer;">
                        <i class="fas fa-arrow-left"></i> Back to Step 1
                    </button>
                    <button type="submit" id="finalSaveBtn" style="background: #10b981; color: white; padding: 14px 50px; border: none; border-radius: 12px; font-weight: 800; cursor: pointer;">CONFIRM & SAVE ALL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="viewBrandModal">
    <div class="modal-container" style="max-width: 900px;">
        <span onclick="$('#viewBrandModal').fadeOut(200)" style="position: absolute; right: 25px; top: 20px; font-size: 28px; cursor: pointer; color: #94a3b8;">&times;</span>
        <h3 id="viewTitle" style="font-weight: 800; margin-bottom: 25px; color: var(--primary);">Product Details</h3>
        <div class="table-responsive">
            <table class="table view-table" style="width:100%">
                <thead>
                    <tr>
                        <th width="15%">Image</th>
                        <th width="25%">Brand Name</th>
                        <th width="20%">Price</th>
                        <th width="40%">Specifications</th>
                    </tr>
                </thead>
                <tbody id="brandsTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let productTable;
    $(document).ready(function() {
        productTable = $('#productDataTable').DataTable({
            ajax: { url: "{{ route('products.list') }}", dataSrc: 'data' },
            columns: [
                { data: 'id' },
                { data: 'product_name', render: d => `<span style="font-weight:800; color:#1e293b;">${d}</span>` },
                { data: 'product_description' },
                { data: 'brands_count', className: 'text-center', render: d => `<span class="badge" style="background:#eef2ff; color:var(--primary); padding:7px 15px; border-radius:30px;">${d} Variations</span>` },
                { 
                    data: null, className: 'text-center', orderable: false,
                    render: function(row) {
                        return `<div class="action-wrapper">
                                    <button onclick="viewBrands(${row.id}, '${row.product_name}')" class="action-btn btn-view" title="View"><i class="fas fa-eye"></i></button>
                                    <a href="{{ url('products/generate-pdf') }}/${row.id}" target="_blank" class="action-btn btn-pdf" title="PDF"><i class="fas fa-file-pdf"></i></a>
                                    <button onclick="deleteProduct(${row.id})" class="action-btn btn-delete" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                </div>`;
                    }
                }
            ]
        });

        $('#masterProductForm').on('submit', function(e) {
            e.preventDefault();
            if(!validateStep('#brandTab')) return;
            let formData = new FormData(this);
            $('#finalSaveBtn').prop('disabled', true).text('Saving...');
            $.ajax({
                url: "{{ route('products.store') }}",
                method: "POST",
                data: formData,
                contentType: false, processData: false,
                success: function() {
                    Swal.fire('Success', 'Inventory Add Sucessfully!', 'success');
                    closeModal();
                    productTable.ajax.reload();
                },
                error: function() { Swal.fire('Error', 'Sync Failed', 'error'); },
                complete: function() { $('#finalSaveBtn').prop('disabled', false).text('CONFIRM & SAVE ALL'); }
            });
        });
    });

    function validateStep(tabId) {
        let isValid = true;
        $(`${tabId} .form-control`).each(function() {
            let field = $(this);
            let val = field.val().trim();
            let error = field.siblings('.invalid-feedback');
            let isFieldValid = true;

            if(val === "" && field.prop('type') !== 'file') isFieldValid = false;
            else if(field.prop('type') === 'file' && field.get(0).files.length === 0) isFieldValid = false;
            else if((field.attr('name') === 'product_name' || field.attr('name') === 'brand_name[]') && val.length < 3) isFieldValid = false;
            else if(field.attr('name') === 'product_description' && val.length < 10) isFieldValid = false;
            else if(field.attr('name') === 'price[]' && (parseFloat(val) < 10 || isNaN(val))) isFieldValid = false;

            if(!isFieldValid) { field.addClass('is-invalid'); error.show(); isValid = false; }
            else { field.removeClass('is-invalid'); error.hide(); }
        });
        return isValid;
    }

    function clearValidation(el) {
        let field = $(el);
        let val = field.val().trim();
        let valid = false;

        if(field.attr('name') === 'price[]') {
            if(val.startsWith('0')) field.val(val.replace(/^0+/, ''));
            if(parseFloat(val) >= 10) valid = true;
        } else if((field.attr('name') === 'product_name' || field.attr('name') === 'brand_name[]') && val.length >= 3) valid = true;
        else if(field.attr('name') === 'product_description' && val.length >= 10) valid = true;
        else if(field.prop('type') === 'file' && field.get(0).files.length > 0) valid = true;
        else if(val !== "" && field.attr('name') === 'detail[]') valid = true;

        if(valid) { field.removeClass('is-invalid'); field.siblings('.invalid-feedback').hide(); }
    }

    function openProductModal() { $('#productModal').fadeIn(200); backToProduct(); }
    function closeModal() { $('#productModal').fadeOut(200); $('#masterProductForm')[0].reset(); $('.form-control').removeClass('is-invalid'); $('.invalid-feedback').hide(); }
    function goToBrands() { if(validateStep('#productTab')) { $('#productTab').hide(); $('#brandTab').fadeIn(300); } }
    function backToProduct() { $('#brandTab').hide(); $('#productTab').fadeIn(300); }

    function addBrandRow() {
        let row = `<div class="brand-row" style="display:none;">
            <div class="form-group"><input type="text" name="brand_name[]" class="form-control" placeholder="Brand" oninput="clearValidation(this)"><div class="invalid-feedback">Min 3 chars.</div></div>
            <div class="form-group"><input type="number" name="price[]" class="form-control" placeholder="Price" onkeypress="return event.charCode >= 49" oninput="clearValidation(this)"><div class="invalid-feedback">Min ₹10.</div></div>
            <div class="form-group"><input type="file" name="brand_image[]" class="form-control" onchange="clearValidation(this)"><div class="invalid-feedback">Required.</div></div>
            <div class="form-group"><input type="text" name="detail[]" class="form-control" placeholder="Specs" oninput="clearValidation(this)"><div class="invalid-feedback">Required.</div></div>
            <button type="button" onclick="this.parentElement.remove()" style="color:var(--danger); border:none; background:none; cursor:pointer;"><i class="fas fa-minus-circle"></i></button>
        </div>`;
        $(row).appendTo('#brandContainer').slideDown(300);
    }

    function viewBrands(id, name) {
        $('#viewTitle').html(`<span style="color:#64748b; font-weight:400;">Product:</span> ${name}`);
        $('#brandsTableBody').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');
        $('#viewBrandModal').fadeIn(200);

        $.ajax({
            url: "{{ url('/get-product-brands') }}/" + id,
            method: "GET",
            success: function(res) {
                let html = '';
                if(res.length > 0) {
                    res.forEach(brand => {
                        let img = brand.brand_image ? `{{ url('/') }}/${brand.brand_image}` : 'https://via.placeholder.com/50';
                        html += `
                            <tr>
                                <td><img src="${img}" style="width:50px; height:50px; border-radius:8px; object-fit:contain; border:1px solid #e2e8f0;"></td>
                                <td><span style="font-weight:700; color:#1e293b;">${brand.brand_name}</span></td>
                                <td><span style="color:#10b981; font-weight:700;">₹${brand.price}</span></td>
                                <td style="color:#64748b; font-size:13px;">${brand.detail}</td>
                            </tr>`;
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center text-muted">No variations found.</td></tr>';
                }
                $('#brandsTableBody').html(html);
            },
            error: function() {
                $('#brandsTableBody').html('<tr><td colspan="4" class="text-center text-danger">Failed to load data.</td></tr>');
            }
        });
    }

    function deleteProduct(id) {
        Swal.fire({
            title: 'Delete Product?',
            text: "All variations will be lost!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/delete-product') }}/" + id,
                    method: "POST",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() {
                        Swal.fire('Deleted!', 'Product removed.', 'success');
                        productTable.ajax.reload();
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection