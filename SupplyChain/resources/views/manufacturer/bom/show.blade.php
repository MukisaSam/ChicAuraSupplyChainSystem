@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Bill of Materials Details</h1>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-6">
            <div class="mb-4">
                <span class="font-semibold text-gray-700">Product:</span>
                <span class="text-gray-800">{{ $billOfMaterial->product->name ?? '-' }}</span>
            </div>
            <div class="mb-4">
                <span class="font-semibold text-gray-700">Components:</span>
                <table class="min-w-full table-auto bg-white rounded shadow mt-2">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Raw Material</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Quantity</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($billOfMaterial->components as $component)
                        <tr class="even:bg-gray-50 hover:bg-indigo-50 transition">
                            <td class="border-t px-4 py-2">{{ $component->rawItem->name ?? '-' }}</td>
                            <td class="border-t px-4 py-2">{{ $component->quantity }}</td>
                            <td class="border-t px-4 py-2">
                                <form action="{{ route('test.delete-component', [$billOfMaterial->id, $component->id]) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline bg-transparent border-0 p-0 m-0 cursor-pointer">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-400 py-4">No components found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mb-4 mt-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Add New Component</h2>
                <form method="POST" action="{{ route('test.add-component', $billOfMaterial->id) }}" class="flex flex-wrap items-center gap-2">
                    @csrf
                    <select name="raw_item_id" class="border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                        <option value="">Select raw material</option>
                        @foreach(App\Models\Item::where('category', 'raw_material')->get() as $rawMaterial)
                            <option value="{{ $rawMaterial->id }}">{{ $rawMaterial->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="quantity" class="border border-gray-300 rounded-lg p-2 w-24 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" min="1" placeholder="Qty" required>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.bom.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to BoMs</a>
</div>
@endsection 