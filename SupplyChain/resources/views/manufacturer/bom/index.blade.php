@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex flex-wrap justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-black">Bill of Materials</h1>
            <div class="w-full sm:w-auto flex justify-end mt-4 sm:mt-0">
                <a href="{{ route('manufacturer.bom.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">Create New BoM</a>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-black">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-black">Product</th>
                        <th class="px-4 py-3 text-left font-semibold text-black">Components</th>
                        <th class="px-4 py-3 text-left font-semibold text-black">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($boms as $bom)
                    <tr class="even:bg-gray-50 hover:bg-indigo-50 transition">
                        <td class="border-t px-4 py-2">{{ $bom->id }}</td>
                        <td class="border-t px-4 py-2">{{ $bom->product->name ?? '-' }}</td>
                        <td class="border-t px-4 py-2">
                            @foreach($bom->components as $component)
                                <span class="inline-block bg-gray-100 rounded px-2 py-1 text-xs mr-1 mb-1">
                                    {{ $component->rawItem->name ?? '-' }} ({{ $component->quantity }})
                                </span>
                            @endforeach
                        </td>
                        <td class="border-t px-4 py-2">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('manufacturer.bom.show', $bom->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-3 rounded-lg shadow transition">View</a>
                                <a href="{{ route('manufacturer.bom.edit', $bom->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-1 px-3 rounded-lg shadow transition">Edit</a>
                                <form action="{{ route('manufacturer.bom.destroy', $bom->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this BoM?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-red-400">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-400 py-8">No Bill of Materials found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-center">
            {{ $boms->links() }}
        </div>
    </div>
</div>
@endsection 