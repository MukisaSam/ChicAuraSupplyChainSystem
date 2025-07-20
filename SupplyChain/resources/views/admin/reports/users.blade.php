@extends('layouts.admin-dashboard')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold mb-4">User Activity Report</h1>
    <p>This is a placeholder for the User Activity Report. The detailed report will be implemented here.</p>
    <a href="{{ route('admin.reports.index') }}" class="text-blue-600 hover:underline">&larr; Back to Reports Center</a>
</div>
@endsection 