@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Supply Requests</h2>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($supplyRequests as $request)
            <tr>
                <td>{{ $request->id }}</td>
                <td>{{ $request->item->name ?? '-' }}</td>
                <td>{{ $request->quantity }}</td>
                <td>{{ $request->due_date ? $request->due_date->format('Y-m-d') : '-' }}</td>
                <td>{{ ucfirst($request->status) }}</td>
                <td>
                    <a href="{{ route('supplier.supply-requests.show', $request) }}" class="btn btn-sm btn-primary">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No supply requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
