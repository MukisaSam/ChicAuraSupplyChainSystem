@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-white mb-4 md:mb-0">Warehouse Management</h1>
        <a href="{{ route('manufacturer.warehouse.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg shadow font-semibold transition">Add Warehouse</a>
    </div>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left font-semibold text-gray-700">Location</th>
                    <th class="py-3 px-6 text-left font-semibold text-gray-700">Capacity</th>
                    <th class="py-3 px-6 text-left font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($warehouses as $warehouse)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-6">{{ $warehouse->location }}</td>
                    <td class="py-3 px-6">{{ $warehouse->capacity }}</td>
                    <td class="py-3 px-6">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('manufacturer.warehouse.show', $warehouse) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-3 rounded-lg shadow transition">View</a>
                            <a href="{{ route('warehouses.inventory-items.index', $warehouse) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-1 px-3 rounded-lg shadow transition">Inventory</a>
                            <a href="{{ route('manufacturer.warehouse.assign-staff', $warehouse) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-1 px-3 rounded-lg shadow transition">Staff</a>
                            <a href="{{ route('manufacturer.warehouse.edit', $warehouse) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-1 px-3 rounded-lg shadow transition">Edit</a>
                            <form action="{{ route('manufacturer.warehouse.destroy', $warehouse) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this warehouse?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-red-400">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $warehouses->links() }}</div>
</div>
@endsection 