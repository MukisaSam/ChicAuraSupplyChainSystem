@extends('layouts.supplier-dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Supplied Items</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mb-6">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div class="flex flex-col">
                        <label for="status" class="form-label text-gray-700 dark:text-gray-200">Status</label>
                        <select name="status" id="status" class="form-select rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label for="delivery_date" class="form-label text-gray-700 dark:text-gray-200">Delivery Date</label>
                        <input type="date" name="delivery_date" id="delivery_date" class="form-input rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:text-white" value="{{ request('delivery_date') }}">
                    </div>
                    <div class="flex gap-2 mt-4 sm:mt-0">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition">Filter</button>
                        <a href="{{ route('supplier.supplied-items.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg shadow hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 transition">Clear</a>
                    </div>
                </form>
            </div>

            <!-- Supplied Items Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Supplied Items</h5>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($suppliedItems as $item)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold">{{ $item->item->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $item->item->description }}</p>
                                    <ul class="text-sm mt-2 space-y-1">
                                        <li><strong>Quantity:</strong> {{ number_format($item->delivered_quantity) }}</li>
                                        <li><strong>Price:</strong> ${{ number_format($item->price, 2) }}</li>
                                        <li><strong>Total:</strong> ${{ number_format($item->price * $item->delivered_quantity, 2) }}</li>
                                        <li><strong>Delivery Date:</strong> {{ $item->delivery_date->format('M d, Y') }}</li>
                                        <li><strong>Status:</strong> <span class="badge ...">{{ ucfirst($item->status) }}</span></li>
                                        <li>
                                            <strong>Quality:</strong>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $item->quality_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                            ({{ $item->quality_rating }}/5)
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <a href="{{ route('supplier.supplied-items.show', $item) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8 text-gray-400">
                                <i class="fas fa-box fa-3x mb-3"></i>
                                <p>No supplied items found.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($suppliedItems->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $suppliedItems->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 