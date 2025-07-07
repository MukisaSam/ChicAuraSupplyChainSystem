@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4 text-white">Warehouse Management</h1>
    <a href="{{ route('manufacturer.warehouse.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">Add Warehouse</a>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Location</th>
                <th class="py-2 px-4 border-b">Capacity</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($warehouses as $warehouse)
            <tr>
                <td class="py-2 px-4 border-b">{{ $warehouse->location }}</td>
                <td class="py-2 px-4 border-b">{{ $warehouse->capacity }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('manufacturer.warehouse.edit', $warehouse) }}" class="text-blue-600">Edit</a>
                    <form action="{{ route('manufacturer.warehouse.destroy', $warehouse) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $warehouses->links() }}</div>
</div>
@endsection 