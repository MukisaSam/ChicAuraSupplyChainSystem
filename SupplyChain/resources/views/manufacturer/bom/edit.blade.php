@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6 max-w-2xl">
    <h1 class="text-2xl font-bold text-white mb-6">Edit Bill of Materials</h1>
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
        <div class="mb-6">
            <label class="block font-semibold text-white mb-1">Product</label>
            <select name="product_id" class="w-full border rounded p-2" required>
                <option value="">Select a product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $billOfMaterial->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-6">
            <label class="block font-semibold text-white mb-1">Components</label>
            <div id="components-list">
                @foreach($billOfMaterial->components as $i => $component)
                <div class="flex items-center mb-2 component-row">
                    <select name="components[]" class="border rounded p-2 mr-2" required>
                        <option value="">Select raw material</option>
                        @foreach($rawMaterials as $item)
                            <option value="{{ $item->id }}" {{ $component->raw_item_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="quantities[]" class="border rounded p-2 w-24 mr-2" min="1" value="{{ $component->quantity }}" required>
                    <button type="button" onclick="removeComponent(this)" class="text-red-600 hover:underline">Remove</button>
                </div>
                @endforeach
            </div>
            <button type="button" onclick="addComponent()" class="bg-green-500 text-white px-3 py-1 rounded mt-2">Add Component</button>
        </div>
        <div class="flex items-center mt-8">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded">Update BoM</button>
            <a href="{{ route('manufacturer.bom.index') }}" class="ml-4 text-white underline">Cancel</a>
        </div>
    </form>
</div>
<script>
function addComponent() {
    const list = document.getElementById('components-list');
    const div = document.createElement('div');
    div.className = 'flex items-center mb-2 component-row';
    div.innerHTML = `
        <select name="components[]" class="border rounded p-2 mr-2" required>
            <option value="">Select raw material</option>
            @foreach($rawMaterials as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        <input type="number" name="quantities[]" class="border rounded p-2 w-24 mr-2" min="1" placeholder="Qty" required>
        <button type="button" onclick="removeComponent(this)" class="text-red-600 hover:underline">Remove</button>
    `;
    list.appendChild(div);
}
function removeComponent(btn) {
    btn.closest('.component-row').remove();
}
</script>
@endsection 