@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6 text-black">Assign Staff to Warehouse</h1>
        <form action="{{ route('manufacturer.warehouse.assign-staff.post', $warehouse) }}" method="POST">
            @csrf
            <div class="mb-6">
                <label for="workforce_id" class="block mb-2 text-sm font-medium text-black">Select Staff Member</label>
                <select name="workforce_id" id="workforce_id" class="w-full border px-3 py-2 rounded" required>
                    <option value="" disabled selected>Select a staff member</option>
                    @foreach($availableWorkforce as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->fullname }} ({{ $staff->job_role ?? 'Staff' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition">Assign</button>
                <a href="{{ route('manufacturer.warehouse.show', $warehouse) }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection 