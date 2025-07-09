@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Production Costs</h1>
    <a href="{{ route('manufacturer.production-costs.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">Add New Cost</a>
    <table class="min-w-full table-auto bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Product</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($costs as $cost)
            <tr>
                <td class="border px-4 py-2">{{ $cost->id }}</td>
                <td class="border px-4 py-2">{{ $cost->item->name ?? '-' }}</td>
                <td class="border px-4 py-2">${{ number_format($cost->amount, 2) }}</td>
                <td class="border px-4 py-2">{{ $cost->date }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('manufacturer.production-costs.show', $cost->id) }}" class="text-blue-600 hover:underline mr-2">View</a>
                    <a href="{{ route('manufacturer.production-costs.edit', $cost->id) }}" class="text-yellow-600 hover:underline mr-2">Edit</a>
                    <form action="{{ route('manufacturer.production-costs.destroy', $cost->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this cost?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-0 p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-gray-400 py-4">No production costs found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $costs->links() }}</div>
</div>
@endsection 