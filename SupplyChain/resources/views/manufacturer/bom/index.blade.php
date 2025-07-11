@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold text-white mb-4">Bill of Materials</h1>
    <a href="{{ route('manufacturer.bom.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">Create New BoM</a>
    <table class="min-w-full table-auto bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Product</th>
                <th class="px-4 py-2">Components</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($boms as $bom)
            <tr>
                <td class="border px-4 py-2">{{ $bom->id }}</td>
                <td class="border px-4 py-2">{{ $bom->product->name ?? '-' }}</td>
                <td class="border px-4 py-2">
                    @foreach($bom->components as $component)
                        <span class="inline-block bg-gray-100 rounded px-2 py-1 text-xs mr-1 mb-1">
                            {{ $component->rawItem->name ?? '-' }} ({{ $component->quantity }})
                        </span>
                    @endforeach
                </td>
                <td class="border px-4 py-2">
                    <a href="{{ route('manufacturer.bom.show', $bom->id) }}" class="text-blue-600 hover:underline">View</a>
                    <a href="{{ route('manufacturer.bom.edit', $bom->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-gray-400 py-4">No Bill of Materials found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $boms->links() }}</div>
</div>
@endsection 