@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Add Employee</h1>
    <form action="{{ route('manufacturer.workforce.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Full Name</label>
            <input type="text" name="fullname" class="w-full border px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Contact Info</label>
            <input type="text" name="contact_info" class="w-full border px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Address</label>
            <input type="text" name="address" class="w-full border px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Job Role</label>
            <input type="text" name="job_role" class="w-full border px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Status</label>
            <select name="status" class="w-full border px-3 py-2" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="On Leave">On Leave</option>
                <option value="Terminated">Terminated</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Hire Date</label>
            <input type="date" name="hire_date" class="w-full border px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Salary</label>
            <input type="number" name="salary" class="w-full border px-3 py-2">
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Add Employee</button>
        <a href="{{ route('manufacturer.workforce.index') }}" class="ml-4 text-gray-600">Cancel</a>
    </form>
</div>
@endsection 