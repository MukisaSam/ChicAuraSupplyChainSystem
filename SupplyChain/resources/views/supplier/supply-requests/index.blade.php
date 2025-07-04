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
                        <a class="nav-link active" href="{{ route('supplier.supply-requests.index') }}">
                            <i class="fas fa-clipboard-list"></i> Supply Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.supplied-items.index') }}">
                            <i class="fas fa-box"></i> Supplied Items
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.analytics.index') }}">
                            <i class="fas fa-chart-line"></i> Analytics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.chat.index') }}">
                            <i class="fas fa-comments"></i> Chat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('supplier.reports.index') }}">
                            <i class="fas fa-file-alt"></i> Reports
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Supply Requests</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ request('due_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('supplier.supply-requests.index') }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Supply Requests Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Supply Requests</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Payment Type</th>
                                    <th>Delivery Method</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supplyRequests as $request)
                                <tr>
                                    <td>#{{ $request->id }}</td>
                                    <td>
                                        <strong>{{ $request->item->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $request->item->description }}</small>
                                    </td>
                                    <td>{{ number_format($request->quantity) }}</td>
                                    <td>
                                        <span class="{{ $request->due_date->isPast() ? 'text-danger' : '' }}">
                                            {{ $request->due_date->format('M d, Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $request->status === 'pending' ? 'warning' : 
                                            ($request->status === 'accepted' || $request->status === 'approved' ? 'success' : 
                                            ($request->status === 'rejected' || $request->status === 'declined' ? 'danger' : 
                                            ($request->status === 'in_progress' ? 'info' : 'secondary'))) }}">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ ucfirst($request->payment_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ ucfirst($request->delivery_method) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('supplier.supply-requests.show', $request) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No supply requests found.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($supplyRequests->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $supplyRequests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 