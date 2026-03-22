<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | Pro Stock Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary: #6366F1;
            --primary-dark: #4F46E5;
            --sidebar-width: 280px;
            --bg-color: #F8FAFC;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-color); margin: 0; display: flex; min-height: 100vh; color: #1e293b; overflow-x: hidden; }
        * { box-sizing: border-box; }

        /* Premium Sidebar */
        .sidebar { width: var(--sidebar-width); background: #0F172A; height: 100vh; position: fixed; display: flex; flex-direction: column; z-index: 1000; }
        .sidebar-header { padding: 40px 30px; display: flex; align-items: center; gap: 15px; }
        .logo-box { background: linear-gradient(135deg, #6366F1, #A855F7); width: 45px; height: 45px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: white; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3); }
        .sidebar-nav { padding: 20px; flex-grow: 1; }
        .nav-item { display: flex; align-items: center; gap: 15px; padding: 14px 20px; color: #94A3B8; text-decoration: none; border-radius: 12px; margin-bottom: 8px; font-size: 14px; font-weight: 600; transition: 0.3s; }
        .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
        .nav-item.active { background: var(--primary); color: white; box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3); }
        .sidebar-footer { padding: 30px; border-top: 1px solid rgba(255,255,255,0.05); }

        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .top-navbar { background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 900; border-bottom: 1px solid #E2E8F0; height: 80px; }
        .status-badge { display: flex; align-items: center; gap: 8px; background: #F0FDF4; color: #166534; padding: 6px 14px; border-radius: 50px; font-size: 11px; font-weight: 700; border: 1px solid #DCFCE7; }
        .avatar-box { width: 42px; height: 42px; background: #F1F5F9; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #475569; position: relative; border: 1px solid #E2E8F0; overflow: hidden; }
        .avatar-box img { width: 100%; height: 100%; object-fit: cover; }

        /* Portfolio Drawer */
        .portfolio-drawer { position: fixed; top: 0; right: -450px; width: 450px; height: 100vh; background: white; z-index: 1050; box-shadow: -20px 0 60px rgba(0,0,0,0.1); transition: 0.6s cubic-bezier(0.165, 0.84, 0.44, 1); display: flex; flex-direction: column; border-left: 1px solid #F1F5F9; }
        .portfolio-drawer.open { right: 0; }
        .drawer-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(8px); z-index: 1040; display: none; opacity: 0; transition: 0.4s; }
        .drawer-overlay.visible { display: block; opacity: 1; }
        .drawer-body { flex: 1; overflow-y: auto; padding: 0 40px; }
        .portfolio-item { display: flex; gap: 18px; padding: 25px 0; border-bottom: 1px solid #F1F5F9; align-items: center; animation: slideInItem 0.4s ease-out; }
        @keyframes slideInItem { from { transform: translateX(20px); opacity:0; } to { transform: translateX(0); opacity:1; } }
        .portfolio-item img { width: 70px; height: 70px; border-radius: 16px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .acquire-btn { background: #111827; color: white; border: none; padding: 24px 40px; font-weight: 800; font-size: 14px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: 0.3s; letter-spacing: 1px; border-top: 1px solid #F1F5F9; }
        .acquire-btn:hover { background: var(--primary); }
        .cart-icon-wrapper { position: relative; cursor: pointer; padding: 10px; border-radius: 12px; transition: 0.2s; }
        .cart-icon-wrapper:hover { background: #F1F5F9; }
        .cart-badge { position: absolute; top: 0; right: 0; background: #EF4444; color: white; font-size: 10px; padding: 2px 6px; border-radius: 50%; border: 2px solid white; font-weight: 800; }

        /* Styles for Footer provided in the include */
        .page-footer { background: #FFFFFF; padding: 60px 0 0; margin-top: auto; color: #1e293b; border-top: 1px solid #E2E8F0; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1.5fr; gap: 40px; max-width: 1400px; margin: 0 auto; padding: 0 40px; }
        .footer-col h4 { margin: 0 0 20px; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: #94A3B8; }
        .footer-col ul { list-style: none; padding: 0; margin: 0; }
        .footer-col ul li { margin-bottom: 12px; }
        .footer-col ul li a { color: #64748B; text-decoration: none; font-size: 14px; transition: 0.2s; }
        .footer-col ul li a:hover { color: var(--primary); }
        .social-icons { display: flex; gap: 15px; margin-top: 20px; }
        .social-icons a { width: 36px; height: 36px; background: #F1F5F9; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #64748B; transition: 0.3s; }
        .social-icons a:hover { background: var(--primary); color: white; transform: translateY(-3px); }

        /* Profile Dropdown Styles (Restored to Simple Version) */
        .profile-container { position: relative; cursor: pointer; }
        .dropdown-menu { 
            position: absolute; top: 60px; right: 0; width: 280px; 
            background: white; border-radius: 20px; border: 1px solid #E2E8F0; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); 
            display: none; flex-direction: column; z-index: 1000; 
            animation: dropdownFade 0.3s ease-out; overflow: hidden;
        }
        @keyframes dropdownFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .dropdown-menu.show { display: flex; }
        .dropdown-header { padding: 25px; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; display: flex; align-items: center; gap: 15px; }
        .dropdown-item { padding: 14px 25px; display: flex; align-items: center; gap: 14px; text-decoration: none; color: #475569; font-size: 14px; font-weight: 700; transition: 0.2s; border: none; background: none; width: 100%; text-align: left; cursor: pointer; }
        .dropdown-item i { width: 20px; color: #94A3B8; font-size: 16px; transition: 0.2s; }
        .dropdown-item:hover { background: #F8FAFC; color: var(--primary); }
        .dropdown-item:hover i { color: var(--primary); transform: scale(1.1); }
        .logout-item { color: #EF4444 !important; border-top: 1px solid #F1F5F9; margin-top: 5px; padding-top: 18px; }
        .logout-item i { color: #EF4444 !important; }
        .logout-item:hover { background: #FEF2F2 !important; }

        @keyframes pulse-cart {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); color: var(--primary); }
            100% { transform: scale(1); }
        }
        .pulse-cart { animation: pulse-cart 0.5s ease-in-out; }
    </style>
</head>
<body>

    <div class="drawer-overlay" id="drawerOverlay" onclick="togglePortfolio()"></div>
    <div class="portfolio-drawer" id="portfolioDrawer">
        <div style="padding: 40px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #F1F5F9;">
            <div>
                <h2 style="margin:0; font-size: 22px; font-weight: 900; letter-spacing: -1px; color: #0F172A;">Portfolio Hub</h2>
                <span id="drawerCount" style="color:var(--primary); font-size:12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">0 Assets Tracked</span>
            </div>
            <button onclick="togglePortfolio()" style="background: #F1F5F9; border: none; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #64748B; cursor: pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="drawer-body" id="portfolioItems"></div>
        <div style="padding: 30px 40px; border-top: 1px solid #F1F5F9; background: #F8FAFC;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <div style="font-size: 14px; color: #64748B; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Estimated Value</div>
                <div id="drawerTotal" style="font-size: 24px; font-weight: 900; color: #0F172A;">₹0.00</div>
            </div>
            <button class="acquire-btn" style="width: 100%; border-radius: 16px; padding: 20px; box-shadow: 0 10px 20px rgba(17, 24, 39, 0.15);" onclick="proceedToPayment()">
                <span>PROCEED TO CHECKOUT</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>

    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo-box"><i class="fas fa-microchip"></i></div>
            <div style="font-weight: 800; font-size: 20px; color: white; letter-spacing: -0.5px;">ProStock <span style="color: var(--primary);">.</span></div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('customer.dashboard') }}" class="nav-item {{ Request::is('customer/dashboard*') ? 'active' : '' }}"><i class="fas fa-compass"></i> Explore Items</a>
            <a href="#" class="nav-item"><i class="fas fa-layer-group"></i> My Orders</a>
            <a href="{{ route('customer.wishlist') }}" class="nav-item {{ Request::is('customer/wishlist*') ? 'active' : '' }}"><i class="fas fa-heart"></i> Saved Items</a>
            <a href="{{ route('customer.profile') }}" class="nav-item {{ Request::is('customer/profile*') ? 'active' : '' }}"><i class="fas fa-user-circle"></i> Account Setting</a>
        </nav>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); width: 100%; padding: 12px; border-radius: 12px; color: #ef4444; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;"><i class="fas fa-power-off"></i> Secure Logout</button>
            </form>
        </div>
    </aside>

    <div class="main-content">
        <header class="top-navbar">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="status-badge"><div style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%; box-shadow: 0 0 10px #22c55e;"></div> Portal Online</div>
            </div>
            <div style="display: flex; align-items: center; gap: 30px;">
                <div class="cart-icon-wrapper" onclick="togglePortfolio()">
                    <i class="fas fa-shopping-cart" style="font-size: 20px; color: #64748B;"></i>
                    <span id="cartCount" class="cart-badge">0</span>
                </div>
                <div style="width: 1px; height: 30px; background: #E2E8F0;"></div>
                
                @php 
                    $user = Auth::guard('customer')->user();
                    $parts = explode(' ', $user->name); 
                    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : '')); 
                @endphp

                <div class="profile-container" id="profileTrigger" onclick="toggleUserDropdown(event)">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="text-align: right;">
                            <div style="font-weight: 700; font-size: 14px;">{{ $user->name }}</div>
                            <div style="font-size: 10px; color: #94A3B8; text-transform: uppercase; font-weight: 800;">Authorized Client</div>
                        </div>
                        <div class="avatar-box">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}">
                            @else
                                {{ $initials }}
                            @endif
                            <div style="position: absolute; bottom: 0; right: 0; width: 12px; height: 12px; background: #22c55e; border-radius: 50%; border: 2px solid white;"></div>
                        </div>
                    </div>

                    <!-- Reverted Dropdown Menu (Simple Version) -->
                    <div class="dropdown-menu" id="userDropdown">
                        <div class="dropdown-header">
                            <div class="avatar-box" style="width: 50px; height: 50px; flex-shrink: 0;">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}">
                                @else
                                    {{ $initials }}
                                @endif
                            </div>
                            <div style="overflow: hidden;">
                                <div style="font-weight: 800; font-size: 15px; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $user->name }}</div>
                                <div style="font-size: 11px; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $user->email }}</div>
                            </div>
                        </div>
                        <a href="{{ route('customer.profile') }}" class="dropdown-item">
                            <i class="fas fa-user-circle"></i> View My Profile
                        </a>
                        <a href="{{ route('customer.profile') }}#security-section" class="dropdown-item">
                            <i class="fas fa-lock"></i> Security Center
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item logout-item">
                                <i class="fas fa-power-off"></i> Secure Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div style="padding: 40px;">@yield('content')</div>

        @include('layouts.customer_footer')
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let portfolio = [];

        // Dynamic Database Sync
        $(document).ready(function() {
            fetchCartFromDb();
        });

        function fetchCartFromDb() {
            $.ajax({
                url: "{{ route('cart.fetch') }}",
                type: 'GET',
                success: function(res) {
                    if(res.status === 'success') {
                        portfolio = res.data;
                        updateHeaderCount();
                        renderPortfolio();
                    }
                },
                error: function(xhr) { console.error("Identity extraction failed from secure vault."); }
            });
        }

        function togglePortfolio() { 
            const drawer = document.getElementById('portfolioDrawer'); 
            const overlay = document.getElementById('drawerOverlay'); 
            drawer.classList.toggle('open'); 
            overlay.classList.toggle('visible'); 
            if (drawer.classList.contains('open')) renderPortfolio(); 
        }

        window.addToPortfolioHub = function(id, name, price, img) { 
            // Optimistic UI Update
            const existingItem = portfolio.find(item => item.id == id);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                portfolio.push({ id, name, price, img, quantity: 1 });
            }
            updateHeaderCount(); 
            renderPortfolio(); 
            
            // Pulse animation for cart icon
            $('.cart-icon-wrapper').addClass('pulse-cart');
            setTimeout(() => $('.cart-icon-wrapper').removeClass('pulse-cart'), 500);

            // Database Sync
            $.ajax({
                url: "{{ route('cart.add') }}",
                type: 'POST',
                data: {
                    product_id: id,
                    quantity: 1,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    // Success silently synced
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Authorization Error', text: 'Failed to sync with secure vault.' });
                }
            });
        };

        function updateHeaderCount() { 
            const uniqueAssets = portfolio.length;
            const totalQuantity = portfolio.reduce((sum, item) => sum + parseInt(item.quantity), 0);
            
            $('#cartCount').text(uniqueAssets); 
            $('#drawerCount').text(`${uniqueAssets} Assets in Portfolio`); 
        }

        function renderPortfolio() {
            const container = $('#portfolioItems');
            const checkoutBtn = $('.acquire-btn');
            
            if (portfolio.length === 0) { 
                container.html('<div style="text-align: center; padding-top: 50%; color: #94A3B8;"><i class="fas fa-shopping-basket" style="font-size: 48px; margin-bottom: 25px; opacity: 0.3;"></i><p style="font-weight: 600; font-size: 15px;">Your digital portfolio is empty.</p></div>'); 
                $('#drawerTotal').text('₹0.00'); 
                checkoutBtn.attr('disabled', true).css({'opacity': '0.3', 'cursor': 'not-allowed', 'background': '#94A3B8'});
                return; 
            } else {
                checkoutBtn.attr('disabled', false).css({'opacity': '1', 'cursor': 'pointer', 'background': '#111827'});
            }
            
            let html = '', total = 0;
            portfolio.forEach((item, index) => { 
                const itemTotal = parseFloat(item.price) * item.quantity;
                total += itemTotal; 
                html += `
                <div class="portfolio-item">
                    <img src="${item.img}">
                    <div style="flex:1;">
                        <div style="font-weight: 800; font-size:14px; color:#0F172A; margin-bottom:4px;">${item.name}</div>
                        <div style="font-size:13px; font-weight:700; color:var(--primary); letter-spacing: 0.5px; margin-bottom: 10px;">₹${parseFloat(item.price).toLocaleString()}</div>
                        <div style="display: flex; align-items: center; gap: 12px; background: #F8FAFC; width: fit-content; padding: 4px 8px; border-radius: 8px; border: 1px solid #E2E8F0;">
                            <button onclick="updateQuantity(${index}, -1)" style="border:none; background:none; color:#64748B; cursor:pointer;"><i class="fas fa-minus" style="font-size: 10px;"></i></button>
                            <span style="font-size: 13px; font-weight: 800; min-width: 20px; text-align: center;">${item.quantity}</span>
                            <button onclick="updateQuantity(${index}, 1)" style="border:none; background:none; color:var(--primary); cursor:pointer;"><i class="fas fa-plus" style="font-size: 10px;"></i></button>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 5px;">₹${itemTotal.toLocaleString()}</div>
                        <button onclick="removeFromPortfolio(${index})" style="background:none; border:none; color:#EF4444; cursor:pointer; font-size: 14px; opacity: 0.5; transition: 0.3s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.5"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>`; 
            });
            container.html(html); 
            $('#drawerTotal').text(`₹${total.toLocaleString()}`);
        }

        window.updateQuantity = function(index, delta) {
            const item = portfolio[index];
            item.quantity += delta;
            
            if (item.quantity <= 0) {
                removeFromPortfolio(index);
            } else {
                updateHeaderCount();
                renderPortfolio();
                
                // Database Sync
                $.ajax({
                    url: "{{ route('cart.update') }}",
                    type: 'POST',
                    data: {
                        product_id: item.id,
                        delta: delta,
                        _token: "{{ csrf_token() }}"
                    }
                });
            }
        };

        window.removeFromPortfolio = function(index) { 
            const productId = portfolio[index].id;
            portfolio.splice(index, 1); 
            updateHeaderCount(); 
            renderPortfolio(); 
            
            // Database Sync
            $.ajax({
                url: "{{ route('cart.remove') }}",
                type: 'POST',
                data: {
                    product_id: productId,
                    _token: "{{ csrf_token() }}"
                }
            });
        };

        function proceedToPayment() {
            if (portfolio.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Empty Portfolio', text: 'Please add items to your portfolio before proceeding to payment.' });
                return;
            }
            
            Swal.fire({
                title: '<div style="font-weight:900; font-size:26px; color:#0F172A; letter-spacing:-1px;">Finalize Your Order</div>',
                html: `
                    <div style="text-align: center; padding: 10px 0;">
                        <div style="background: #F8FAFC; border-radius: 20px; padding: 25px; border: 1px solid #E2E8F0; margin-bottom: 25px;">
                            <div style="font-size: 11px; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">Order Total</div>
                            <div style="font-size: 34px; font-weight: 900; color: #111827;">${$('#drawerTotal').text()}</div>
                            <div style="font-size: 13px; color: #64748B; margin-top: 5px; font-weight: 600;">Acquiring ${portfolio.length} products today</div>
                        </div>
                        
                        <div style="font-size: 14px; font-weight: 600; color: #64748B; margin-bottom: 5px;">Secure checkout powered by ProStock Terminal</div>
                    </div>
                `,
                iconHtml: '<div style="font-size: 40px; color: #6366F1;"><i class="fas fa-check-circle"></i></div>',
                showCancelButton: true,
                confirmButtonColor: '#6366F1',
                cancelButtonColor: '#F1F5F9',
                confirmButtonText: 'YES, PAY NOW',
                cancelButtonText: 'NO, GO BACK',
                padding: '40px',
                background: '#ffffff',
                borderRadius: '32px',
                customClass: {
                    container: 'swal-top-layer',
                    confirmButton: 'premium-confirm-btn',
                    cancelButton: 'premium-cancel-btn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    togglePortfolio();
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your payment has been successfully processed.',
                        icon: 'success',
                        confirmButtonColor: '#111827',
                        background: '#ffffff',
                        borderRadius: '24px'
                    });
                }
            });
        }

        // Dropdown Logic
        function toggleUserDropdown(event) {
            event.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('show');
        }
        window.onclick = function(event) {
            if (!event.target.closest('.profile-container')) {
                const dropdowns = document.getElementsByClassName("dropdown-menu");
                for (let i = 0; i < dropdowns.length; i++) {
                    dropdowns[i].classList.remove('show');
                }
            }
        }
    </script>
    @yield('extra_js')
</body>
</html>
