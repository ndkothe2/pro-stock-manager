@extends('layouts.customer_master')

@section('title', 'Market Ecosystem')

@section('content')
<div style="display: flex; gap: 40px; align-items: flex-start;">
    
    <!-- Advanced Category Navigator (Inner Sidebar) -->
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
        <!-- Advanced Header Banner -->
        <div style="margin-bottom: 45px; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); padding: 50px; border-radius: 32px; color: white; position: relative; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid rgba(255,255,255,0.05);">
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
                    <p style="margin:0; color: #94a3b8; font-size: 16px; line-height: 1.7; font-weight: 500;">Real-time access to the most liquid and valuable assets within the ProStock merchant ecosystem. Explore curated high-valuation inventory.</p>
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

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                @foreach($products as $product)
                <div class="product-asset-card" style="background: white; border-radius: 20px; border: 1px solid #F3F4F6; box-shadow: var(--card-shadow); transition: 0.3s; cursor: pointer; overflow: hidden;">
                    <div style="height: 180px; background: #F9FAFB; overflow: hidden;">
                        @php
                            $firstBrand = $product->brands->first();
                            $image = ($firstBrand && $firstBrand->brand_image) ? asset($firstBrand->brand_image) : 'https://placehold.co/600x400/f1f5f9/64748b?text=' . urlencode($product->product_name);
                        @endphp
                        <img src="{{ $image }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="padding: 24px;">
                        <h4 style="margin: 0 0 8px; color: #111827; font-size: 16px; font-weight: 700;">{{ $product->product_name }}</h4>
                        <div style="font-size: 12px; color: #6B7280; line-height: 1.5; margin-bottom: 20px; height: 36px; overflow: hidden;">{{ $product->product_description }}</div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #F9FAFB; padding-top: 16px;">
                            <div>
                                <span style="font-size: 18px; font-weight: 800; color: #111827;">₹{{ number_format($product->brands->min('price'), 2) }}</span>
                            </div>
                            <span style="background: #ECFDF5; color: #059669; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">VERIFIED SOURCE</span>
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
        font-size: 14px;
        font-family: inherit;
    }
    .cat-btn i { width: 16px; font-size: 14px; color: #9CA3AF; }
    .cat-btn:hover { background: #F9FAFB; color: #111827; }
    .cat-btn.active { background: #EEF2FF; color: #4F46E5; font-weight: 700; }
    .cat-btn.active i { color: #4F46E5; }
    .count-badge { margin-left: auto; background: #F3F4F6; padding: 2px 8px; border-radius: 8px; font-size: 11px; color: #6B7280; font-weight: 600; }
    .cat-btn.active .count-badge { background: #E0E7FF; color: #4F46E5; }

    .product-asset-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
</style>

@endsection

@section('extra_js')
<script>
    function filterCategory(slug) {
        // Update UI
        $('.cat-btn').removeClass('active');
        $(`button[onclick="filterCategory('${slug}')"]`).addClass('active');

        if (slug === 'ALL') {
            $('.category-section').fadeIn(300);
        } else {
            $('.category-section').hide();
            $(`#section-${slug}`).fadeIn(300);
        }
    }

    $(document).ready(function() {
        $('#categorySearch').on('input', function() {
            let val = $(this).val().toLowerCase();
            $('#categoryList .cat-btn').each(function() {
                let txt = $(this).find('span').first().text().toLowerCase();
                if (txt === 'all active items') return; // Skip "All"
                
                if (txt.includes(val)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
@endsection
