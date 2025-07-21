@extends('layouts.supplier-dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1><strong>Supplied Items</strong></h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Supplied Items Table -->
            <div class="card">
                <div class="card-body">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($suppliedItems as $item)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold">{{ $item->item->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $item->item->description }}</p>
                                    <ul class="text-sm mt-2 space-y-1">
                                        <li><strong>Quantity:</strong> {{ number_format($item->delivered_quantity) }}</li>
                                        <li><strong>Delivered Date:</strong> {{ $item->delivery_date ? $item->delivery_date->format('M d, Y') : '-' }}</li>
                                        <li><strong>Status:</strong> <span class="inline-block px-2 py-1 rounded text-xs font-semibold bg-blue-200 text-blue-800">{{ ucfirst($item->status) }}</span></li>
                                        <li><strong>Notes:</strong> {{ $item->notes ?? '-' }}</li>
                                    </ul>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8 text-gray-400">
                                <i class="fas fa-box fa-3x mb-3"></i>
                                <p>No completed supplied items found.</p>
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