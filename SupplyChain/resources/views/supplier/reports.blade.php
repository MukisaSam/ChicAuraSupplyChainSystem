@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.supply-requests.index') }}">
                            <i class="fas fa-clipboard-list"></i> Supply Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.supplied-items.index') }}">
                            <i class="fas fa-box"></i> Supplied Items
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.analytics') }}">
                            <i class="fas fa-chart-line"></i> Analytics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.chat') }}">
                            <i class="fas fa-comments"></i> Chat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('supplier.reports') }}">
                            <i class="fas fa-file-alt"></i> Reports
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Reports & Analytics</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-download"></i> Export PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- Report Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="report_type" class="form-label">Report Type</label>
                            <select name="report_type" id="report_type" class="form-select">
                                <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Monthly Report</option>
                                <option value="quarterly" {{ request('report_type') == 'quarterly' ? 'selected' : '' }}>Quarterly Report</option>
                                <option value="yearly" {{ request('report_type') == 'yearly' ? 'selected' : '' }}>Yearly Report</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Monthly Performance Report -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Monthly Performance Report ({{ date('Y') }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Quantity Supplied</th>
                                    <th>Revenue</th>
                                    <th>Average Rating</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyReport as $report)
                                <tr>
                                    <td>{{ date('F', mktime(0, 0, 0, $report->month, 1)) }}</td>
                                    <td>{{ number_format($report->quantity) }}</td>
                                    <td>${{ number_format($report->revenue, 2) }}</td>
                                    <td>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $report->avg_rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="ms-1">({{ number_format($report->avg_rating, 1) }})</span>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $performance = ($report->quantity > 1000) ? 'Excellent' : 
                                                         (($report->quantity > 500) ? 'Good' : 'Average');
                                            $badgeClass = ($performance == 'Excellent') ? 'success' : 
                                                        (($performance == 'Good') ? 'info' : 'warning');
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">{{ $performance }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Item Performance Report -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Item Performance Report</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Total Quantity</th>
                                    <th>Average Price</th>
                                    <th>Average Rating</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($itemReport as $report)
                                <tr>
                                    <td>
                                        <strong>{{ $report->item->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $report->item->description }}</small>
                                    </td>
                                    <td>{{ number_format($report->total_quantity) }}</td>
                                    <td>${{ number_format($report->avg_price, 2) }}</td>
                                    <td>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $report->avg_rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="ms-1">({{ number_format($report->avg_rating, 1) }})</span>
                                        </div>
                                    </td>
                                    <td>${{ number_format($report->total_quantity * $report->avg_price, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Performance Charts -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Revenue Trend</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quality Rating Trend</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="ratingChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-primary">{{ number_format($monthlyReport->sum('quantity')) }}</h3>
                            <p class="card-text">Total Items Supplied</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-success">${{ number_format($monthlyReport->sum('revenue'), 2) }}</h3>
                            <p class="card-text">Total Revenue</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-warning">{{ number_format($monthlyReport->avg('avg_rating'), 1) }}</h3>
                            <p class="card-text">Average Rating</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-info">{{ $itemReport->count() }}</h3>
                            <p class="card-text">Items Supplied</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyReport->pluck('month')->map(function($month) { return date('F', mktime(0, 0, 0, $month, 1)); })) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode($monthlyReport->pluck('revenue')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Rating Chart
    const ratingCtx = document.getElementById('ratingChart').getContext('2d');
    new Chart(ratingCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyReport->pluck('month')->map(function($month) { return date('F', mktime(0, 0, 0, $month, 1)); })) !!},
            datasets: [{
                label: 'Average Rating',
                data: {!! json_encode($monthlyReport->pluck('avg_rating')) !!},
                backgroundColor: 'rgba(255, 205, 86, 0.8)',
                borderColor: 'rgb(255, 205, 86)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5
                }
            }
        }
    });
});
</script>
@endsection 