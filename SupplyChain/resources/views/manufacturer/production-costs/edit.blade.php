@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold text-white mb-4">Edit Production Cost</h1>
    <form method="POST" action="{{ route('manufacturer.production-costs.update', $productionCost->id) }}" class="max-w-lg bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-semibold mb-1">Product</label>
            <select name="item_id" class="w-full border rounded p-2" required>
                <option value="">Select a product</option>
                @foreach(App\Models\Item::where('type', 'finished_product')->get() as $product)
                    <option value="{{ $product->id }}" @if($product->id == $productionCost->item_id) selected @endif>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Amount</label>
            <input type="number" name="amount" class="w-full border rounded p-2" min="0" step="0.01" value="{{ $productionCost->amount }}" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Date</label>
            <input type="date" name="date" class="w-full border rounded p-2" value="{{ $productionCost->date }}" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" class="w-full border rounded p-2">{{ $productionCost->notes }}</textarea>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Update Cost</button>
        <a href="{{ route('manufacturer.production-costs.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection 