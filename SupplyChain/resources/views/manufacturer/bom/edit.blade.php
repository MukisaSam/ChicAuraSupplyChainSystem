@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Edit Bill of Materials</h1>
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('manufacturer.bom.update', $billOfMaterial->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Product</label>
                <select name="product_id" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="">Select a product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $billOfMaterial->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Components</label>
                <div id="components-list">
                    @foreach($billOfMaterial->components as $i => $component)
                    <div class="flex items-center mb-2 component-row">
                        <select name="components[]" class="border border-gray-300 rounded-lg p-2 mr-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                            <option value="">Select raw material</option>
                            @foreach($rawMaterials as $item)
                                <option value="{{ $item->id }}" {{ $component->raw_item_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="quantities[]" class="border border-gray-300 rounded-lg p-2 w-24 mr-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" min="1" value="{{ $component->quantity }}" required>
                        <button type="button" onclick="removeComponent(this)" class="text-red-600 hover:underline">Remove</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" onclick="addComponent()" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded mt-2 transition">Add Component</button>
            </div>
            <div class="flex items-center mt-8">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition">Update BoM</button>
                <a href="{{ route('manufacturer.bom.index') }}" class="ml-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">Cancel</a>
            </div>
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
    div.className = 'flex items-center mb-2 component-row';
    div.innerHTML = `
        <select name="components[]" class="border border-gray-300 rounded-lg p-2 mr-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
            <option value="">Select raw material</option>
            @foreach($rawMaterials as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        <input type="number" name="quantities[]" class="border border-gray-300 rounded-lg p-2 w-24 mr-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" min="1" placeholder="Qty" required>
        <button type="button" onclick="removeComponent(this)" class="text-red-600 hover:underline">Remove</button>
    `;
    list.appendChild(div);
}
function removeComponent(btn) {
    btn.closest('.component-row').remove();
}
</script>
@endsection 