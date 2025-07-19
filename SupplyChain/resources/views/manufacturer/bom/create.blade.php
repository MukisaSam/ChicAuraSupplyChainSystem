@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6 text-black">Create Bill of Materials</h1>
        <form method="POST" action="{{ route('manufacturer.bom.store') }}">
            @csrf
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Product</label>
                <select name="product_id" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="">Select a product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Components & Quantities</label>
                <div id="components-list">
                    <div class="flex mb-2">
                        <select name="components[]" class="border border-gray-300 rounded-lg p-2 mr-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                            <option value="">Select raw material</option>
                            @foreach($rawMaterials as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="quantities[]" class="border border-gray-300 rounded-lg p-2 w-24 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" min="1" placeholder="Qty" required>
                    </div>
                </div>
                <button type="button" onclick="addComponent()" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded transition">Add Component</button>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Notes</label>
                <textarea name="notes" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">Create BoM</button>
        </form>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.bom.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to BoMs</a>
</div>
<script>
function addComponent() {
    const list = document.getElementById('components-list');
    const div = document.createElement('div');
    div.className = 'flex mb-2';
    div.innerHTML = `
        <select name="components[]" class="border border-gray-300 rounded-lg p-2 mr-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
            <option value="">Select raw material</option>
            @foreach($rawMaterials as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        <input type="number" name="quantities[]" class="border border-gray-300 rounded-lg p-2 w-24 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" min="1" placeholder="Qty" required>
        <button type="button" onclick="this.parentNode.remove()" class="ml-2 text-red-600">Remove</button>
    `;
    list.appendChild(div);
}
</script>
@endsection 