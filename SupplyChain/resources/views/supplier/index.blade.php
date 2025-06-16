@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('supplier.dashboard') }}">
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
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Supplier Dashboard</h1>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Active Requests</h5>
                            <p class="card-text display-6">{{ $supplyRequests->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Supplied</h5>
                            <p class="card-text display-6">{{ $suppliedItems->sum('delivered_quantity') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Average Rating</h5>
                            <p class="card-text display-6">{{ number_format($suppliedItems->avg('quality_rating'), 1) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Revenue</h5>
                            <p class="card-text display-6">${{ number_format($suppliedItems->sum(function($item) { return $item->price * $item->delivered_quantity; }), 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Supply Requests -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Recent Supply Requests</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplyRequests->take(5) as $request)
                                <tr>
                                    <td>{{ $request->item->name }}</td>
                                    <td>{{ $request->quantity }}</td>
                                    <td>{{ $request->due_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'accepted' ? 'success' : 'danger') }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('supplier.supply-requests.show', $request) }}" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Supplied Items -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Supplied Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Delivery Date</th>
                                    <th>Quality Rating</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliedItems->take(5) as $item)
                                <tr>
                                    <td>{{ $item->item->name }}</td>
                                    <td>{{ $item->delivered_quantity }}</td>
                                    <td>{{ $item->delivery_date->format('M d, Y') }}</td>
                                    <td>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $item->quality_rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->status === 'delivered' ? 'success' : 'info' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 