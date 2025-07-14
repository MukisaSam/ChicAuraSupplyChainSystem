@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-8 mb-8">
        <h1 class="text-2xl font-bold mb-6 text-indigo-700">Warehouse Details</h1>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Location:</span>
            <span class="ml-2 text-gray-900">{{ $warehouse->location }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Capacity:</span>
            <span class="ml-2 text-gray-900">{{ $warehouse->capacity }}</span>
        </div>
        <div class="mt-8 flex justify-end gap-2">
            <a href="{{ route('manufacturer.warehouse.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">Back</a>
            <a href="{{ route('manufacturer.warehouse.edit', $warehouse) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">Edit</a>
            <form action="{{ route('manufacturer.warehouse.destroy', $warehouse) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this warehouse?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">Delete</button>
            </form>
        </div>
    </div>

    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-indigo-700">Assigned Staff</h2>
            <a href="{{ route('manufacturer.warehouse.assign-staff', $warehouse) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">Assign Staff</a>
        </div>
        @if($warehouse->workforce->count())
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded-lg">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b text-left">Name</th>
                        <th class="px-4 py-2 border-b text-left">Role</th>
                        <th class="px-4 py-2 border-b"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($warehouse->workforce as $staff)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border-b">{{ $staff->name }}</td>
                        <td class="px-4 py-2 border-b">{{ $staff->role ?? 'Staff' }}</td>
                        <td class="px-4 py-2 border-b">
                            <form action="{{ route('manufacturer.warehouse.remove-staff', [$warehouse, $staff]) }}" method="POST" onsubmit="return confirm('Remove this staff member from the warehouse?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-gray-500">No staff assigned to this warehouse yet.</div>
        @endif
    </div>
</div>
@endsection 