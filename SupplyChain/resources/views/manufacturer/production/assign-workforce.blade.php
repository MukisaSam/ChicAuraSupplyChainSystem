@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold text-black mb-6 text-center">Assign Workforce to Work Order #{{ $workOrder->id }}</h1>
        <form method="POST" action="{{ route('manufacturer.production.assign-workforce.store', $workOrder->id) }}">
            @csrf
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Select Workforce Member</label>
                <select name="workforce_id" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="">Select a member</option>
                    @foreach($workforce as $member)
                        <option value="{{ $member->id }}">{{ $member->fullname }} ({{ $member->job_role ?? 'Staff' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Role/Task (optional)</label>
                <input type="text" name="role" class="w-full border border-gray-300 rounded-lg p-2">
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">Assign</button>
        </form>
        <div class="mt-6 text-center">
            <a href="{{ route('manufacturer.production.show', ['production' => $workOrder->id]) }}" class="text-indigo-600 hover:underline">&larr; Back to Work Order</a>
        </div>
    </div>
</div>
@endsection 