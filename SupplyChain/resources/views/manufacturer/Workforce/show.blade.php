@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6 text-indigo-700">Employee Details</h1>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Full Name:</span>
            <span class="ml-2 text-gray-900">{{ $workforce->fullname }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Email:</span>
            <span class="ml-2 text-gray-900">{{ $workforce->email }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Contact Info:</span>
            <span class="ml-2 text-gray-900">{{ $workforce->contact_info }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Address:</span>
            <span class="ml-2 text-gray-900">{{ $workforce->address }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Job Role:</span>
            <span class="ml-2 text-gray-900">{{ $workforce->job_role }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Status:</span>
            <span class="ml-2 text-gray-900">{{ $workforce->status }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Hire Date:</span>
            <span class="ml-2 text-gray-900">{{ $workforce->hire_date }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Salary:</span>
            <span class="ml-2 text-gray-900">${{ number_format($workforce->salary, 2) }}</span>
        </div>
        <div class="mt-8 flex justify-end">
            <a href="{{ route('manufacturer.workforce.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">Back</a>
        </div>
    </div>
</div>
@endsection 