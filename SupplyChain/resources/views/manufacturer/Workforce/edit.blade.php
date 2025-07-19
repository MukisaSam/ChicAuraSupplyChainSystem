@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6 text-black">Edit Employee</h1>
    <form action="{{ route('manufacturer.workforce.update', $workforce) }}" method="POST" class="bg-white p-6 rounded-lg shadow-lg max-w-xl mx-auto">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1">Full Name</label>
            <input type="text" name="fullname" class="w-full border px-3 py-2" value="{{ $workforce->fullname }}" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border px-3 py-2" value="{{ $workforce->email }}" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Contact Info</label>
            <input type="text" name="contact_info" class="w-full border px-3 py-2" value="{{ $workforce->contact_info }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Address</label>
            <input type="text" name="address" class="w-full border px-3 py-2" value="{{ $workforce->address }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Job Role</label>
            <input type="text" name="job_role" class="w-full border px-3 py-2" value="{{ $workforce->job_role }}" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Status</label>
            <select name="status" class="w-full border px-3 py-2" required>
                <option value="Active" @if($workforce->status=='Active') selected @endif>Active</option>
                <option value="Inactive" @if($workforce->status=='Inactive') selected @endif>Inactive</option>
                <option value="On Leave" @if($workforce->status=='On Leave') selected @endif>On Leave</option>
                <option value="Terminated" @if($workforce->status=='Terminated') selected @endif>Terminated</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Hire Date</label>
            <input type="date" name="hire_date" class="w-full border px-3 py-2" value="{{ $workforce->hire_date }}">
        </div>
        <div class="mb-4">
            <label for="salary" class="block text-sm font-medium text-black mb-2">Salary (UGX)</label>
            <input type="number" name="salary" id="salary" class="w-full border px-3 py-2" value="{{ $workforce->salary }}">
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition">Update Employee</button>
        <a href="{{ route('manufacturer.workforce.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection 