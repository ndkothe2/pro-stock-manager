@extends('layouts.customer_master')

@section('title', 'Market Ecosystem')

@section('content')
<div style="display: flex; gap: 40px; align-items: flex-start;">
    
    <!-- Advanced Category Navigator -->
    <div style="width: 300px; background: white; border-radius: 24px; padding: 24px; border: 1px solid #F3F4F6; box-shadow: var(--card-shadow); position: sticky; top: 40px;">
        <div style="margin-bottom: 24px;">
            <h3 style="margin: 0 0 16px; font-size: 14px; color: #9CA3AF; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Navigation Hub</h3>
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 12px; padding: 10px 16px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-search" style="font-size: 12px; color: #9CA3AF;"></i>
                <input type="text" id="categorySearch" placeholder="Filter categories..." style="background: transparent; border: none; outline: none; font-size: 13px; width: 100%; font-weight: 500;">
            </div>
        </div>

        <div id="categoryList" style="display: flex; flex-direction: column; gap: 8px;">
            <button class="cat-btn active" onclick="filterCategory('ALL')">
                <i class="fas fa-microchip"></i> <span>All Active Items</span>
                <span class="count-badge">{{ collect($groupedProducts)->flatten(1)->count() }}</span>
            </button>
            @foreach($groupedProducts as $category => $products)
            <button class="cat-btn" onclick="filterCategory('{{ Str::slug($category) }}')">
                <i class="fas fa-tag"></i> <span>{{ $category }}</span>
                <span class="count-badge">{{ $products->count() }}</span>
            </button>
            @endforeach
        </div>
    </div>

    <!-- Main Display Section -->
    <div style="flex: 1;">
        <!-- Standardized Header Banner (Total Match with Profile) -->
        <div style="margin-bottom: 40px; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); padding: 50px; border-radius: 32px; color: white; position: relative; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid rgba(255,255,255,0.05);">
            <!-- Decorative Elements -->
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(99, 102, 241, 0.1); border-radius: 50%; filter: blur(40px);"></div>
            <div style="position: absolute; bottom: -30px; left: 10%; width: 150px; height: 150px; background: rgba(168, 85, 247, 0.1); border-radius: 50%; filter: blur(30px);"></div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2;">
                <div style="max-width: 600px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                        <span style="background: rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px);">Live Market Feed</span>
                        <div style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%; animation: pulse 2s infinite;"></div>
                    </div>
                    <h1 style="margin:0; font-size: 38px; font-weight: 800; letter-spacing: -1.5px; line-height: 1.1; margin-bottom: 15px;">Portfolio Explorer <span style="color: #818cf8;">.</span></h1>
                    <p style="margin:0; color: #94a3b8; font-size: 16px; line-height: 1.7; font-weight: 500;">Real-time access to the most liquid and valuable assets within the ProStock merchant ecosystem.</p>
                </div>
                <div style="background: rgba(255,255,255,0.03); width: 100px; height: 100px; border-radius: 24px; display: flex; align-items: center; justify-content: center; transform: rotate(12deg); border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
                    <i class="fas fa-rocket" style="font-size: 40px; color: #818cf8; text-shadow: 0 0 20px rgba(129, 140, 248, 0.5);"></i>
                </div>
            </div>
        </div>

        <!-- Dynamic Product Content -->
        @foreach($groupedProducts as $category => $products)
        <div class="category-section" id="section-{{ Str::slug($category) }}" style="margin-bottom: 50px;">
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 24px;">
                <h2 style="margin:0; font-size: 14px; font-weight: 800; color: #111827; text-transform: uppercase; letter-spacing: 2px;">{{ $category }}</h2>
                <div style="flex: 1; height: 2px; background: #F3F4F6;"></div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
                @foreach($products as $product)
                <div class="product-asset-card" style="background: white; border-radius: 24px; border: 1px solid #F3F4F6; box-shadow: var(--card-shadow); transition: 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); cursor: pointer; overflow: hidden; position: relative;"
                     @php
                        $firstBrand = $product->brands->first();
                        $price = $product->brands->min('price');
                        $image = ($firstBrand && $firstBrand->brand_image) ? asset($firstBrand->brand_image) : 'https://placehold.co/600x400/f1f5f9/64748b?text=' . urlencode($product->product_name);
                     @endphp
                     onclick="quickView('{{ $product->id }}', '{{ addslashes($product->product_name) }}', '{{ $price }}', '{{ $image }}', '{{ addslashes($product->product_description) }}')">
                    
                    <!-- Top Actions -->
                    <div style="position: absolute; top: 15px; right: 15px; z-index: 10;">
                        <button class="icon-btn-glass" title="Add to Wishlist" onclick="event.stopPropagation(); toggleWishlist(this, '{{ addslashes($product->product_name) }}')">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>

                    <!-- Image Area -->
                    <div class="card-image-wrapper" style="height: 220px; background: #F9FAFB; overflow: hidden; position: relative;">
                        <img src="{{ $image }}" style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s;">
                        
                        <!-- Hover Overlay -->
                        <div class="card-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s; backdrop-filter: blur(4px);">
                            <div style="background: white; color: #0f172a; border: none; padding: 12px 24px; border-radius: 50px; font-weight: 800; font-size: 12px; letter-spacing: 1px; transform: translateY(20px); transition: 0.3s;">VIEW SPECS</div>
                        </div>

                        <!-- Category Badge -->
                        <div style="position: absolute; bottom: 12px; left: 15px; background: rgba(255,255,255,0.9); padding: 4px 10px; border-radius: 50px; font-size: 10px; font-weight: 800; color: #4F46E5; box-shadow: 0 4px 10px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
                            {{ strtoupper($category) }}
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div style="padding: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <div style="display: flex; gap: 2px; color: #FBBF24; font-size: 12px;">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            </div>
                            <span style="font-size: 11px; font-weight: 700; color: #059669; display: flex; align-items: center; gap: 5px;">
                                <div style="width: 6px; height: 6px; background: #22c55e; border-radius: 50%;"></div> LIVE
                            </span>
                        </div>

                        <h4 style="margin: 0 0 10px; color: #1e293b; font-size: 18px; font-weight: 800; letter-spacing: -0.5px;">{{ $product->product_name }}</h4>
                        <div style="font-size: 13px; color: #64748B; line-height: 1.6; margin-bottom: 25px; height: 42px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            {{ $product->product_description }}
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #F1F5F9; padding-top: 20px;">
                            <div>
                                <div style="font-size: 11px; color: #94A3B8; font-weight: 700; text-transform: uppercase; margin-bottom: 2px;">Market Value</div>
                                <span style="font-size: 22px; font-weight: 900; color: #0f172a;">₹{{ number_format($price, 2) }}</span>
                            </div>
                            <button class="add-cart-btn" onclick="event.stopPropagation(); addToCart('{{ $product->id }}', '{{ addslashes($product->product_name) }}', '{{ $price }}', '{{ $image }}')" title="Add to Portfolio">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div id="noResults" style="display: none; text-align: center; padding: 80px 20px;">
            <i class="fas fa-search-minus" style="font-size: 40px; color: #D1D5DB; margin-bottom: 20px;"></i>
            <h3 style="color: #6B7280;">No assets found in this category.</h3>
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }
    .cat-btn {
        width: 100%; display: flex; align-items: center; gap: 12px; padding: 14px 16px; background: transparent; border: none; border-radius: 14px; cursor: pointer; transition: 0.2s; text-align: left; color: #4B5563; font-weight: 500; font-size: 14px; font-family: inherit;
    }
    .cat-btn i { width: 16px; font-size: 14px; color: #9CA3AF; }
    .cat-btn:hover { background: #F9FAFB; color: #111827; }
    .cat-btn.active { background: #EEF2FF; color: #4F46E5; font-weight: 700; }
    .cat-btn.active i { color: #4F46E5; }
    .count-badge { margin-left: auto; background: #F3F4F6; padding: 2px 8px; border-radius: 8px; font-size: 11px; color: #6B7280; font-weight: 600; }
    
    .product-asset-card:hover { transform: translateY(-10px); box-shadow: 0 30px 40px -10px rgba(0,0,0,0.12); }
    .product-asset-card:hover .card-overlay { opacity: 1; }
    .product-asset-card:hover .card-overlay div { transform: translateY(0); }
    .product-asset-card:hover .card-image-wrapper img { transform: scale(1.1); }

    .icon-btn-glass {
        width: 40px; height: 40px; background: rgba(255,255,255,0.9); border: none; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #1e293b; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.05); backdrop-filter: blur(5px);
    }
    .icon-btn-glass:hover { background: #EEF2FF; color: #4F46E5; transform: scale(1.1); }
    .icon-btn-glass.active i { color: #f43f5e; font-weight: 900; }

    .add-cart-btn {
        width: 50px; height: 50px; background: #111827; color: white; border: none; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 18px; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 15px rgba(17, 24, 39, 0.2);
    }
    .add-cart-btn:hover { background: #4F46E5; transform: scale(1.1) rotate(90deg); }
</style>

@endsection

@section('extra_js')
<script>
    function filterCategory(slug) {
        $('.cat-btn').removeClass('active');
        $(`button[onclick="filterCategory('${slug}')"]`).addClass('active');
        if (slug === 'ALL') { $('.category-section').fadeIn(300); } else { $('.category-section').hide(); $(`#section-${slug}`).fadeIn(300); }
    }

    function addToCart(id, name, price, img) {
        if (typeof window.addToPortfolioHub === 'function') {
            window.addToPortfolioHub(id, name, price, img);
        }
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true });
        Toast.fire({ icon: 'success', title: `[${name}] Added to Portfolio!` });
    }

    function toggleWishlist(btn, name) {
        $(btn).toggleClass('active');
        const icon = $(btn).find('i');
        if ($(btn).hasClass('active')) {
            icon.removeClass('far').addClass('fas');
            Swal.fire({ toast: true, position: 'top-end', timer: 1500, showConfirmButton: false, icon: 'info', title: `[${name}] Saved to Intel` });
        } else { icon.removeClass('fas').addClass('far'); }
    }

    function quickView(id, name, price, img, desc) {
        Swal.fire({
            title: `<div style="text-align:left; font-weight:800; font-size:24px;">${name}</div>`,
            html: `
                <div style="text-align:left; animation: fadeIn 0.4s ease-out;">
                    <img src="${img}" style="width:100%; height:250px; object-fit:cover; border-radius:20px; margin-bottom:20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <div style="font-size:14px; color:#64748b; line-height:1.7; margin-bottom:20px;">${desc}</div>
                    <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:20px; border-radius:16px;">
                        <div>
                            <div style="font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase;">Valuation / Unit</div>
                            <div style="font-size:24px; font-weight:900; color:#0f172a;">₹${parseFloat(price).toLocaleString()}</div>
                        </div>
                        <button onclick="Swal.close(); addToCart('${id}', '${name}', '${price}', '${img}')" style="background:#111827; color:white; border:none; padding:15px 30px; border-radius:14px; font-weight:800; cursor:pointer;">ACQUIRE NOW</button>
                    </div>
                </div>
            `,
            showConfirmButton: false, width: '600px', padding: '30px', background: '#ffffff', borderRadius: '32px'
        });
    }

    $(document).ready(function() {
        $('#categorySearch').on('input', function() {
            let val = $(this).val().toLowerCase();
            $('#categoryList .cat-btn').each(function() {
                let txt = $(this).find('span').first().text().toLowerCase();
                if (txt === 'all active items') return;
                if (txt.includes(val)) { $(this).show(); } else { $(this).hide(); }
            });
        });
    });
</script>
@endsection
