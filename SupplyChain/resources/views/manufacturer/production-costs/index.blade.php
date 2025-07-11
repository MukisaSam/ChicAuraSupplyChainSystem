@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8">
    <div class="flex flex-wrap justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Production Costs</h1>
        <div class="w-full sm:w-auto flex justify-end mt-4 sm:mt-0">
            <a href="{{ route('manufacturer.production-costs.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">Add New Cost</a>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Number</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Amount</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($costs as $cost)
                <tr class="even:bg-gray-50 hover:bg-indigo-50 transition">
                    <td class="border-t px-4 py-2">{{ $cost->id }}</td>
                    <td class="border-t px-4 py-2">{{ $cost->item->name ?? '-' }}</td>
                    <td class="border-t px-4 py-2">${{ number_format($cost->amount, 2) }}</td>
                    <td class="border-t px-4 py-2">{{ $cost->date }}</td>
                    <td class="border-t px-4 py-2">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('manufacturer.production-costs.show', $cost->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-3 rounded-lg shadow transition">View</a>
                            <a href="{{ route('manufacturer.production-costs.edit', $cost->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-1 px-3 rounded-lg shadow transition">Edit</a>
                            <form action="{{ route('manufacturer.production-costs.destroy', $cost->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this cost?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-red-400">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-400 py-8">No production costs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex justify-center">
        {{ $costs->links() }}
    </div>
</div>
@endsection 