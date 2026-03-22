@extends('layouts.customer_master')

@section('title', 'Saved Intel')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    
    <!-- Premium Header Area (Mirroring Dashboard for Brand Consistency) -->
    <div style="margin-bottom: 40px; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); padding: 50px; border-radius: 32px; color: white; position: relative; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid rgba(255,255,255,0.05);">
        <!-- Decorative Elements -->
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(99, 102, 241, 0.1); border-radius: 50%; filter: blur(40px);"></div>
        <div style="position: absolute; bottom: -30px; left: 10%; width: 150px; height: 150px; background: rgba(168, 85, 247, 0.1); border-radius: 50%; filter: blur(30px);"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2;">
            <div style="max-width: 600px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                    <span style="background: rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px);">Observation Vault</span>
                    <div style="width: 8px; height: 8px; background: #f43f5e; border-radius: 50%; animation: pulse-wish 2s infinite;"></div>
                </div>
                <h1 style="margin:0; font-size: 38px; font-weight: 800; letter-spacing: -1.5px; line-height: 1.1; margin-bottom: 15px;">Saved Intel <span style="color: #f43f5e;">.</span></h1>
                <p style="margin:0; color: #94a3b8; font-size: 16px; line-height: 1.7; font-weight: 500;">Monitor and acquire your priority assets within the ProStock merchant ecosystem. Flagged for immediate action.</p>
            </div>
            <div style="background: rgba(255,255,255,0.03); width: 100px; height: 100px; border-radius: 24px; display: flex; align-items: center; justify-content: center; transform: rotate(-8deg); border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
                <i class="fas fa-heart" style="font-size: 40px; color: #f43f5e; text-shadow: 0 0 20px rgba(244, 63, 94, 0.4);"></i>
            </div>
        </div>
    </div>

    @if($wishlistItems->isEmpty())
        <!-- Empty State (Enhanced Design) -->
        <div style="background: white; padding: 120px 40px; border-radius: 32px; text-align: center; border: 1px solid #F3F4F6; box-shadow: var(--card-shadow);">
            <div style="width: 120px; height: 120px; background: #F9FAFB; border-radius: 36px; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; border: 1px solid #EEF2FF;">
                <i class="far fa-heart" style="font-size: 48px; color: #D1D5DB;"></i>
            </div>
            <h2 style="font-size: 28px; font-weight: 800; color: #111827; letter-spacing: -1px; margin-bottom: 16px;">Intel Vault is Vacant</h2>
            <p style="color: #6B7280; font-size: 16px; margin-bottom: 40px; max-width: 450px; margin-left: auto; margin-right: auto; line-height: 1.8;">You haven't flagged any assets for observation yet. Start exploring the marketplace to build your strategic portfolio.</p>
            <a href="{{ route('customer.dashboard') }}" style="display: inline-flex; align-items: center; gap: 12px; background: #111827; color: white; text-decoration: none; padding: 18px 36px; border-radius: 18px; font-weight: 800; transition: 0.4s; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                EXPLORE MARKETPLACE <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    @else
        <!-- Active Wishlist Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
            @foreach($wishlistItems as $item)
                @php 
                    $product = $item->product;
                    $firstBrand = $product->brands->first();
                    $price = $product->brands->min('price');
                    $image = ($firstBrand) ? $firstBrand->brand_image : 'https://placehold.co/600x400/f1f5f9/64748b?text=' . urlencode($product->product_name);
                    
                    // Fallback for image
                    $localPath = public_path($image);
                    if (empty($image) || (!empty($image) && !str_starts_with($image, 'http') && !file_exists($localPath))) {
                        if (stripos($product->product_name, 'Smartphone') !== false) $image = "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=400";
                        else if (stripos($product->product_name, 'Laptop') !== false) $image = "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=400";
                        else $image = "https://images.unsplash.com/photo-1523206489230-c012c64b2b48?q=80&w=400";
                    } else if (!empty($image) && !str_starts_with($image, 'http')) {
                        $image = asset($image);
                    }
                @endphp
                <div class="wishlist-item-card" style="background: white; border-radius: 24px; border: 1px solid #F3F4F6; box-shadow: var(--card-shadow); transition: 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); overflow: hidden; position: relative;">
                    
                    <!-- Remove Action -->
                    <div style="position: absolute; top: 15px; right: 15px; z-index: 10;">
                        <button class="remove-wish-btn" title="Remove from Saved" onclick="toggleWishlistPage('{{ $product->id }}', this)">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>

                    <!-- Image Area -->
                    <div style="height: 220px; background: #F9FAFB; overflow: hidden; position: relative;">
                        <img src="{{ $image }}" style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s;">
                        <div style="position: absolute; bottom: 12px; left: 15px; background: rgba(255,255,255,0.9); padding: 4px 10px; border-radius: 50px; font-size: 10px; font-weight: 800; color: #f43f5e; box-shadow: 0 4px 10px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
                            SAVED INTEL
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div style="padding: 24px;">
                        <h4 style="margin: 0 0 10px; color: #1e293b; font-size: 18px; font-weight: 800; letter-spacing: -0.5px;">{{ $product->product_name }}</h4>
                        <div style="font-size: 13px; color: #64748B; line-height: 1.6; margin-bottom: 25px; height: 42px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            {{ $product->product_description }}
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #F1F5F9; padding-top: 20px;">
                            <div>
                                <div style="font-size: 11px; color: #94A3B8; font-weight: 700; text-transform: uppercase; margin-bottom: 2px;">Market Value</div>
                                <span style="font-size: 20px; font-weight: 900; color: #111827;">₹{{ number_format($price, 2) }}</span>
                            </div>
                            <button class="add-to-cart-action-btn" onclick="addToCart('{{ $product->id }}', '{{ addslashes($product->product_name) }}', '{{ $price }}', '{{ $image }}')" title="Acquire immediately">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    @keyframes pulse-wish {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(244, 63, 94, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(244, 63, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(244, 63, 94, 0); }
    }
    
    .wishlist-item-card:hover { transform: translateY(-10px); box-shadow: 0 30px 40px -10px rgba(0,0,0,0.1); }
    .wishlist-item-card:hover img { transform: scale(1.08); }

    .remove-wish-btn {
        width: 40px; height: 40px; background: white; border: none; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #f43f5e; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .remove-wish-btn:hover { background: #fee2e2; transform: scale(1.1); }

    .add-to-cart-action-btn {
        width: 48px; height: 48px; background: #111827; color: white; border: none; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 16px; cursor: pointer; transition: 0.3s;
    }
    .add-to-cart-action-btn:hover { background: #4f46e5; transform: scale(1.1) rotate(90deg); }

    /* Premium Swal Overrides */
    .swal-premium-popup { border-radius: 32px !important; padding: 40px !important; }
    .swal-premium-title { font-weight: 900 !important; font-size: 28px !important; color: #0F172A !important; letter-spacing: -1.2px !important; }
    .swal-premium-confirm { 
        background: #111827 !important; color: white !important; border-radius: 16px !important; padding: 16px 35px !important; font-weight: 800 !important; font-size: 13px !important; letter-spacing: 1px !important; border: none !important; transition: 0.3s !important; box-shadow: 0 10px 20px rgba(17, 24, 39, 0.2) !important; margin: 0 10px !important;
    }
    .swal-premium-confirm:hover { background: #f43f5e !important; transform: translateY(-2px) !important; box-shadow: 0 15px 25px rgba(244, 63, 94, 0.3) !important; }
    .swal-premium-cancel { 
        background: #F1F5F9 !important; color: #64748B !important; border-radius: 16px !important; padding: 16px 35px !important; font-weight: 800 !important; font-size: 13px !important; letter-spacing: 1px !important; border: none !important; transition: 0.3s !important; margin: 0 10px !important;
    }
    .swal-premium-cancel:hover { background: #E2E8F0 !important; color: #0F172A !important; }
</style>
@endsection

@section('extra_js')
<script>
    function toggleWishlistPage(id, btn) {
        const itemName = $(btn).closest('.wishlist-item-card').find('h4').text();
        const itemImg = $(btn).closest('.wishlist-item-card').find('img').attr('src');

        Swal.fire({
            title: 'Remove from Saved?',
            html: `
                <div style="text-align: center; padding: 10px 0;">
                    <div style="background: #F8FAFC; border-radius: 24px; padding: 30px; border: 1px solid #E2E8F0; margin-bottom: 30px; display: flex; align-items: center; gap: 24px; text-align: left; position: relative; overflow: hidden;">
                        <div style="position: absolute; top: -20px; right: -20px; width: 60px; height: 60px; background: rgba(244, 63, 94, 0.05); border-radius: 50%;"></div>
                        <img src="${itemImg}" style="width: 80px; height: 80px; border-radius: 16px; object-fit: cover; box-shadow: 0 8px 15px rgba(0,0,0,0.08); position: relative; z-index: 2;">
                        <div style="position: relative; z-index: 2;">
                            <div style="font-size: 10px; font-weight: 800; color: #f43f5e; text-transform: uppercase; letter-spacing: 2.5px; margin-bottom: 6px;">Saved for later</div>
                            <div style="font-size: 20px; font-weight: 900; color: #0F172A; letter-spacing: -0.5px;">${itemName}</div>
                            <div style="font-size: 12px; color: #94A3B8; font-weight: 600; margin-top: 4px;">Item ID: PSM-${id.padStart(5, '0')}</div>
                        </div>
                    </div>
                    <div style="font-size: 15px; font-weight: 600; color: #475569; line-height: 1.6; max-width: 320px; margin: 0 auto;">Are you sure you want to remove this item from your saved list?</div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'YES, REMOVE',
            cancelButtonText: 'NO, KEEP IT',
            background: '#ffffff',
            reverseButtons: true,
            customClass: {
                popup: 'swal-premium-popup',
                title: 'swal-premium-title',
                confirmButton: 'swal-premium-confirm',
                cancelButton: 'swal-premium-cancel'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('wishlist.toggle') }}",
                    type: 'POST',
                    data: {
                        product_id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        $(btn).closest('.wishlist-item-card').fadeOut(400, function() {
                            $(this).remove();
                            if ($('.wishlist-item-card').length === 0) {
                                location.reload();
                            }
                        });
                        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
                        Toast.fire({ icon: 'success', title: 'Asset discarded from vault.' });
                    }
                });
            }
        });
    }

    function addToCart(id, name, price, img) {
        if (typeof window.addToPortfolioHub === 'function') {
            window.addToPortfolioHub(id, name, price, img);
            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
            Toast.fire({ icon: 'success', title: `[${name}] Added to Portfolio!` });
        }
    }
</script>
@endsection
