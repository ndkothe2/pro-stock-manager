@extends('layouts.admin_master')

@section('title', 'Project Intelligence & Analytics')

@section('content')
<div style="background: var(--white); padding: 35px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.04);">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px;">
        <div>
            <h2 style="margin:0; font-weight: 800; color: #1e293b; font-size: 28px; letter-spacing: -0.5px;">Project Intelligence</h2>
            <p style="margin:5px 0 0; color: #64748b; font-size: 14px;">Real-time ecosystem analytics and performance monitoring</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 20px; border-radius: 14px; display: flex; align-items: center; gap: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <i class="fas fa-microchip" style="color: var(--primary);"></i>
                <span style="font-size: 13px; font-weight: 700; color: #1e293b;">Engine v4.2.0</span>
            </div>
            <button onclick="window.print()" style="background: var(--primary); color: white; border: none; padding: 12px 20px; border-radius: 14px; cursor: pointer; font-weight: 700; font-family: 'Laila'; display: flex; align-items: center; gap: 8px; transition: 0.3s;">
                <i class="fas fa-file-export"></i> Export Intelligence
            </button>
        </div>
    </div>

    <!-- Executive Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 25px; margin-bottom: 45px;">
        <!-- Valuation Card -->
        <div style="background: linear-gradient(135deg, #1e293b, #334155); padding: 30px; border-radius: 24px; color: white; position: relative; overflow: hidden; box-shadow: 0 15px 30px rgba(30, 41, 59, 0.15);">
            <div style="position: absolute; right: -20px; top: -20px; opacity: 0.1; font-size: 120px;">
                <i class="fas fa-wallet"></i>
            </div>
            <h4 style="margin:0; font-size: 12px; opacity: 0.7; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Gross Valuation</h4>
            <p style="font-size: 32px; font-weight: 800; margin: 15px 0 0;">₹{{ number_format($totalStockValue, 2) }}</p>
            <div style="margin-top: 20px; font-size: 11px; background: rgba(255,255,255,0.15); padding: 6px 14px; border-radius: 10px; display: inline-flex; align-items: center; gap: 6px; backdrop-filter: blur(5px);">
                <i class="fas fa-chart-line"></i> +8.4% Assets growth
            </div>
        </div>

        <!-- Product Depth -->
        <div style="background: white; padding: 30px; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h4 style="margin:0; font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Product Nodes</h4>
                    <p style="font-size: 32px; font-weight: 800; margin: 15px 0 0; color: #1e293b;">{{ $totalProducts }}</p>
                </div>
                <div style="width: 45px; height: 45px; background: #eff6ff; color: #3b82f6; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fas fa-cubes"></i>
                </div>
            </div>
            <p style="margin: 20px 0 0; font-size: 12px; color: #10b981; font-weight: 600;"><i class="fas fa-shield-alt"></i> 100% Data Integrity</p>
        </div>

        <!-- System Merchants -->
        <div style="background: white; padding: 30px; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h4 style="margin:0; font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Merchants</h4>
                    <p style="font-size: 32px; font-weight: 800; margin: 15px 0 0; color: #1e293b;">{{ $totalSellers }}</p>
                </div>
                <div style="width: 45px; height: 45px; background: #fdf2f8; color: #db2777; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <p style="margin: 20px 0 0; font-size: 12px; color: #6366f1; font-weight: 600;"><i class="fas fa-check-circle"></i> Active Partnerships</p>
        </div>
    </div>

    <!-- Main Visualizations -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px;">
        <!-- System Activity Line Chart -->
        <div style="background: #fbfcfe; border-radius: 28px; padding: 30px; border: 1px solid #f1f5f9;">
            <div style="margin-bottom: 25px;">
                <h3 style="margin:0; font-size: 18px; color: #1e293b; font-weight: 700;">Engine Activity (15D)</h3>
                <p style="margin: 5px 0 0; font-size: 12px; color: #94a3b8;">Daily system requests and data modifications</p>
            </div>
            <div style="height: 300px;">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- Seller Valuation Bar Chart -->
        <div style="background: white; border-radius: 28px; padding: 30px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
            <div style="margin-bottom: 25px;">
                <h3 style="margin:0; font-size: 18px; color: #1e293b; font-weight: 700;">Merchant Valuation</h3>
                <p style="margin: 5px 0 0; font-size: 12px; color: #1e293b;">Top-tier asset holders by gross valuation</p>
            </div>
            <div style="height: 300px;">
                <canvas id="valuationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Lower Panels -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Detailed Top Sellers Table -->
        <div style="background: white; border-radius: 28px; padding: 35px; border: 1px solid #f1f5f9; box-shadow: 0 10px 40px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h3 style="margin:0; font-size: 18px; color: #1e293b; font-weight: 700;">Performance Matrix</h3>
                <div style="background: #eff6ff; color: #3b82f6; padding: 6px 14px; border-radius: 10px; font-size: 11px; font-weight: 700;">TOP 5 PERFORMERS</div>
            </div>
            
            <table style="width: 100%; border-collapse: separate; border-spacing: 0 12px;">
                <thead>
                    <tr style="text-align: left; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">
                        <th style="padding: 10px 15px;">Merchant Authority</th>
                        <th style="padding: 10px 15px;">Assets</th>
                        <th style="padding: 10px 15px;">Valuation</th>
                        <th style="padding: 10px 15px; text-align: right;">Momentum</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSellers as $seller)
                    <tr style="background: #fbfcfe; border-radius: 15px; transition: 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fbfcfe'">
                        <td style="padding: 20px 15px; border-radius: 15px 0 0 15px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 40px; height: 40px; background: white; border: 1px solid #e2e8f0; color: var(--primary); display: flex; align-items: center; justify-content: center; border-radius: 10px; font-weight: 800; font-size: 14px;">
                                    {{ substr($seller->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #1e293b; font-size: 14px;">{{ strtoupper($seller->name) }}</div>
                                    <div style="font-size: 11px; color: #94a3b8;">Verified Merchant</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 20px 15px; font-weight: 600; color: #64748b; font-size: 14px;">
                            {{ $seller->products_count }} Products
                        </td>
                        <td style="padding: 20px 15px; font-weight: 700; color: #1e293b; font-size: 14px;">
                            ₹{{ number_format($seller->total_valuation, 2) }}
                        </td>
                        <td style="padding: 20px 15px; text-align: right; border-radius: 0 15px 15px 0;">
                            <span style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700;">
                                <i class="fas fa-bolt"></i> OPTIMIZED
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Category Distribution Chart -->
        <div style="background: #1e293b; border-radius: 28px; padding: 35px; color: white;">
            <h3 style="margin:0 0 10px; font-size: 18px; font-weight: 700;">Asset Distribution</h3>
            <p style="margin:0 0 35px; font-size: 12px; opacity: 0.6;">Top 5 products by brand density</p>
            
            <div style="height: 250px; position: relative;">
                <canvas id="productChart"></canvas>
            </div>
            
            <div style="margin-top: 40px; display: grid; gap: 15px;">
                @foreach($productDistribution as $index => $pd)
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 8px; height: 8px; background: {{ ['#6366f1', '#ec4899', '#f59e0b', '#10b981', '#3b82f6'][$index] }}; border-radius: 50%;"></div>
                        <span style="font-size: 13px; opacity: 0.8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 120px;">{{ $pd->product_name }}</span>
                    </div>
                    <span style="font-weight: 700; font-size: 13px;">{{ $pd->brands_count }} BRANDS</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Laila', sans-serif";
    Chart.defaults.color = "#94a3b8";

    // Engine Activity Chart
    new Chart(document.getElementById('activityChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyActivity->pluck('date')) !!},
            datasets: [{
                label: 'Activity Logs',
                data: {!! json_encode($dailyActivity->pluck('count')) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Merchant Valuation Chart
    new Chart(document.getElementById('valuationChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($topSellers->pluck('name')) !!},
            datasets: [{
                label: 'Gross valuation (₹)',
                data: {!! json_encode($topSellers->pluck('total_valuation')) !!},
                backgroundColor: '#cbd5e1',
                hoverBackgroundColor: '#6366f1',
                borderRadius: 8,
                barThickness: 35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { borderDash: [5, 5], color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Product Distribution Chart
    new Chart(document.getElementById('productChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($productDistribution->pluck('product_name')) !!},
            datasets: [{
                data: {!! json_encode($productDistribution->pluck('brands_count')) !!},
                backgroundColor: ['#6366f1', '#ec4899', '#f59e0b', '#10b981', '#3b82f6'],
                borderWidth: 0,
                hoverOffset: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%',
            plugins: { legend: { display: false } }
        }
    });
</script>
@endsection
