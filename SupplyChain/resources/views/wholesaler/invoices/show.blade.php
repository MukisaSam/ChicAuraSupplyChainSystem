@extends('manufacturer.layouts.dashboard')

@section('content')
<div class="container">
    <h2>Invoice Details</h2>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Invoice #: {{ $invoice->invoice_number }}</h5>
            <p><strong>Order #:</strong> {{ $invoice->order->order_number ?? '-' }}</p>
            <p><strong>Amount:</strong> ${{ number_format($invoice->amount, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
            <p><strong>Due Date:</strong> {{ $invoice->due_date->format('Y-m-d') }}</p>
            <hr>
            <h6>Order Items</h6>
            <ul>
                @foreach($invoice->order->orderItems as $item)
                    <li>{{ $item->item->name }} ({{ $item->quantity }} x ${{ number_format($item->unit_price, 2) }})</li>
                @endforeach
            </ul>
        </div>
    </div>
    <a href="{{ route('invoices.index') }}" class="btn btn-secondary mt-3">Back to Invoices</a>
</div>
@endsection 