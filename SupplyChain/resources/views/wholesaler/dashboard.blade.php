@extends('layouts.app')

@section('content')
<style>
    body { font-family: 'Nunito', sans-serif; background: #f3f4f6; }
    .header { background: #f88b8b; padding: 0 24px; display: flex; align-items: center; height: 56px; }
    .header-title { font-size: 2rem; font-weight: bold; color: #222; margin-right: 12px; }
    .search-bar { flex: 1; margin: 0 24px; display: flex; align-items: center; }
    .search-bar input { width: 250px; padding: 6px 12px; border: 1px solid #ddd; border-radius: 4px; }
    .profile { width: 32px; height: 32px; border-radius: 50%; background: #eee; margin-left: 12px; }
    .main-content { display: flex; min-height: calc(100vh - 56px); }
    .sidebar { width: 180px; background: #fff; border-right: 1px solid #e5e7eb; padding-top: 24px; }
    .sidebar a { display: flex; align-items: center; padding: 14px 24px; color: #222; text-decoration: none; font-weight: 600; font-size: 1.1em; }
    .sidebar a:hover, .sidebar a.active { background: #f3f4f6; border-left: 4px solid #f88b8b; }
    .sidebar span { margin-right: 12px; font-size: 1.3em; }
    .dashboard-area { flex: 1; padding: 32px 40px; }
    .dashboard-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px; }
    .left-col, .right-col { display: flex; flex-direction: column; gap: 24px; }
    .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #0001; padding: 22px 28px; }
    .card h3 { margin: 0 0 10px 0; font-size: 1.1em; font-weight: 700; }
    .big-number { font-size: 2.2rem; font-weight: bold; margin: 10px 0; }
    .order-summary { display: flex; justify-content: space-between; margin-top: 10px; }
    .order-summary div { text-align: center; }
    .notifications { min-height: 120px; }
    .notif-msg { background: #e0e7ff; border-radius: 4px; padding: 4px 8px; margin: 4px 0; display: inline-block; }
    .notif-user { font-size: 0.95em; color: #555; margin-bottom: 2px; }
    .notif-time { font-size: 0.8em; color: #888; }
    .chart { min-height: 160px; }
    @media (max-width: 900px) {
        .dashboard-grid { grid-template-columns: 1fr; }
        .dashboard-area { padding: 16px; }
    }
</style>
<div class="header">
    <span class="header-title">ChicAura</span>
    <span style="font-weight:600; font-size:1.2em; margin-right:24px;">Supply Chain System</span>
    <div class="search-bar">
        <input type="text" placeholder="Search">
        <span style="margin-left:8px;"><i class="fa fa-search"></i></span>
    </div>
    <span style="font-size:1.1em; margin-right:18px;">Wholesaler Dashboard</span>
    <div class="profile"></div>
    <div class="profile"></div>
    <span style="margin-left:10px; font-size:1.5em;">&#9776;</span>
</div>
<div class="main-content">
    <div class="sidebar">
        <a href="#" class="active"><span>🏠</span>HOME</a>
        <a href="#"><span>📦</span>ORDERS</a>
        <a href="#"><span>📊</span>ANALYTICS</a>
        <a href="#"><span>💬</span>CHAT</a>
        <a href="#"><span>📄</span>REPORTS</a>
    </div>
    <div class="dashboard-area">
        <div class="dashboard-grid">
            <div class="left-col">
                <div class="card">
                    <h3>Current approved Unit prices</h3>
                    <ol style="margin:0 0 0 18px;">
                        <li>Trousers shs4000</li>
                        <li>Shirts shs5000</li>
                    </ol>
                </div>
                <div class="card">
                    <h3>Total Products Recieved</h3>
                    <div class="big-number">100 Units</div>
                    <div style="margin-top:10px;">Last Supply Date<br><b>04/5/2025</b></div>
                </div>
                <div class="card">
                    <h3>Order Summary</h3>
                    <div class="order-summary">
                        <div>
                            <div style="font-size:1.3em; font-weight:700;">10</div>
                            <div>Orders</div>
                        </div>
                        <div>
                            <div style="font-size:1.3em; font-weight:700;">6</div>
                            <div>Pending</div>
                        </div>
                        <div>
                            <div style="font-size:1.3em; font-weight:700;">4</div>
                            <div>Received</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-col">
                <div class="card notifications">
                    <h3>Recent Notifications</h3>
                    <div style="margin-top:10px;">
                        <div class="notif-user">Jay (Engineering):</div>
                        <div class="notif-msg">I wish to negotiate the new prices. Can we talk?</div>
                        <div class="notif-time">11:30 AM</div>
                    </div>
                </div>
                <div class="card chart">
                    <h3>Unit Price Variation</h3>
                    <canvas id="priceChart" width="350" height="110"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('priceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jan', 'Feb'],
            datasets: [
                {
                    label: 'Shirts',
                    data: [2000, 3000, 2500, 4000, 3500, 5000, 5250],
                    borderColor: 'blue',
                    backgroundColor: 'rgba(59,130,246,0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Trousers',
                    data: [4000, 6000, 3500, 7000, 5250, 3500, 6000],
                    borderColor: 'red',
                    backgroundColor: 'rgba(239,68,68,0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            plugins: { legend: { display: true } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection