@extends('layouts.supplier-dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
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
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mb-6">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div class="flex flex-col">
                        <label for="status" class="form-label text-gray-700 dark:text-gray-200">Status</label>
                        <select name="status" id="status" class="form-select rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label for="due_date" class="form-label text-gray-700 dark:text-gray-200">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-input rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:text-white" value="{{ request('due_date') }}">
                    </div>
                    <div class="flex gap-2 mt-4 sm:mt-0">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition">Filter</button>
                        <a href="{{ route('supplier.supply-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg shadow hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 transition">Clear</a>
                    </div>
                </form>
            </div>
            <!-- Supply Requests Card Grid (Tailwind) -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h5 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">All Supply Requests</h5>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($supplyRequests as $request)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 flex flex-col justify-between transform transition duration-200 hover:scale-105 hover:shadow-2xl">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs text-gray-400">#{{ $request->id }}</span>
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-{{ $request->status === 'pending' ? 'yellow-100 text-yellow-800' : ($request->status === 'accepted' || $request->status === 'approved' ? 'green-100 text-green-800' : ($request->status === 'rejected' || $request->status === 'declined' ? 'red-100 text-red-800' : ($request->status === 'in_progress' ? 'blue-100 text-blue-800' : 'gray-200 text-gray-800'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $request->item->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-300">{{ $request->item->description }}</p>
                                </div>
                                <ul class="text-sm text-gray-700 dark:text-gray-200 mb-3 space-y-1">
                                    <li><span class="font-semibold">Quantity:</span> {{ number_format($request->quantity) }}</li>
                                    <li><span class="font-semibold">Due Date:</span> <span class="{{ $request->due_date->isPast() ? 'text-red-600' : '' }}">{{ $request->due_date->format('M d, Y') }}</span></li>
                                    <li><span class="font-semibold">Payment:</span> {{ ucfirst($request->payment_type) }}</li>
                                    <li><span class="font-semibold">Delivery:</span> {{ ucfirst($request->delivery_method) }}</li>
                                </ul>
                            </div>
                            <div class="mt-2 flex justify-end">
                                <a href="{{ route('supplier.supply-requests.show', $request) }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700 transition">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-8 text-gray-400">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No supply requests found.</p>
                        </div>
                    @endforelse
                </div>
                <!-- Pagination -->
                @if($supplyRequests->hasPages())
                    <div class="flex justify-center mt-6">
                        {{ $supplyRequests->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection 