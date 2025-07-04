@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Supplier Analytics</h2>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Supplied</h5>
                    <p class="card-text display-6">{{ $stats['total_supplied'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Average Rating</h5>
                    <p class="card-text display-6">{{ number_format($stats['average_rating'], 1) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Active Requests</h5>
                    <p class="card-text display-6">{{ $stats['active_requests'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="card-text display-6">${{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Supply Trends (Monthly)</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Total Supplied</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supplyTrends as $trend)
                    <tr>
                        <td>{{ DateTime::createFromFormat('!m', $trend->month)->format('F') }}</td>
                        <td>{{ $trend->total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 