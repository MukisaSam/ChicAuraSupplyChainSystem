@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Create Bill of Materials</h1>
    <form method="POST" action="{{ route('manufacturer.bom.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Product</label>
            <select name="product_id" class="w-full border rounded p-2" required>
                <option value="">Select a product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Components & Quantities</label>
            <div id="components-list">
                <div class="flex mb-2">
                    <select name="components[]" class="border rounded p-2 mr-2" required>
                        <option value="">Select raw material</option>
                        @foreach($rawMaterials as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="quantities[]" class="border rounded p-2 w-24" min="1" placeholder="Qty" required>
                </div>
            </div>
            <button type="button" onclick="addComponent()" class="bg-green-500 text-white px-2 py-1 rounded">Add Component</button>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" class="w-full border rounded p-2"></textarea>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Create BoM</button>
    </form>
</div>
<script>
function addComponent() {
    const list = document.getElementById('components-list');
    const div = document.createElement('div');
    div.className = 'flex mb-2';
    div.innerHTML = `
        <select name="components[]" class="border rounded p-2 mr-2" required>
            <option value="">Select raw material</option>
            @foreach($rawMaterials as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        <input type="number" name="quantities[]" class="border rounded p-2 w-24" min="1" placeholder="Qty" required>
        <button type="button" onclick="this.parentNode.remove()" class="ml-2 text-red-600">Remove</button>
    `;
    list.appendChild(div);
}
</script>
@endsection 