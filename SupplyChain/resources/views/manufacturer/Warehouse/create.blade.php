@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl text-white font-bold mb-4">Add Warehouse</h1>
    <form action="{{ route('manufacturer.warehouse.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-lg max-w-xl mx-auto">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Location</label>
            <input type="text" name="location" class="w-full border px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Capacity</label>
            <input type="number" name="capacity" class="w-full border px-3 py-2">
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition">Add Warehouse</button>
        <a href="{{ route('manufacturer.warehouse.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection 