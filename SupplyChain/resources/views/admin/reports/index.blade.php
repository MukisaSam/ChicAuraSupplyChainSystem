@extends('layouts.admin-dashboard')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold mb-4">Reports Center</h1>
    <p class="mb-6 text-gray-600">Generate and download reports about orders, users, and product activities.</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-5 rounded-lg shadow-md flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-semibold mb-2">Sales Report</h2>
                <p class="text-gray-500 mb-4">Overview of all sales transactions within a specified date range.</p>
            </div>
            <a href="{{ route('admin.reports.sales') }}" aria-label="Generate Sales Report" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md">Generate</a>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-md flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-semibold mb-2">User Activity Report</h2>
                <p class="text-gray-500 mb-4">Detailed activity of all registered users including sign-ups and orders.</p>
            </div>
            <a href="{{ route('admin.reports.users') }}" aria-label="Generate User Activity Report" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md">Generate</a>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-md flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-semibold mb-2">Inventory Report</h2>
                <p class="text-gray-500 mb-4">Current stock levels and product availability summaries.</p>
            </div>
            <a href="{{ route('admin.reports.inventory') }}" aria-label="Generate Inventory Report" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md">Generate</a>
        </div>
    </div>
    <div class="bg-white p-5 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold mb-4">Export Options</h2>
        <form action="{{ route('admin.reports.export') }}" method="POST" class="flex items-center space-x-4">
            @csrf
            <select name="report_type" class="border rounded px-3 py-2 w-1/3">
                <option value="sales">Sales Report</option>
                <option value="users">User Activity Report</option>
                <option value="inventory">Inventory Report</option>
            </select>
            <button type="submit" aria-label="Export selected report as PDF" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">Export PDF</button>
        </form>
    </div>
</div>
@endsection
