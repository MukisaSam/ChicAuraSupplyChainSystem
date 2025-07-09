@extends('manufacturer.layouts.dashboard')

@section('content')
<div class="container">
    <button onclick="window.print()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded mb-4 print:hidden">
        <i class="fas fa-print mr-2"></i> Print Invoice
    </button>
    <div id="invoice-content">
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
    </div>
    <a href="{{ route('wholesaler.invoices.index') }}" class="btn btn-secondary mt-3 print:hidden">Back to Invoices</a>
</div>
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #invoice-content, #invoice-content * {
            visibility: visible;
        }
        #invoice-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100vw;
            background: white;
            color: black;
        }
        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endsection 