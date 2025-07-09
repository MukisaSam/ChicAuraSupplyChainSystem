@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Add Production Cost</h1>
    <form method="POST" action="{{ route('manufacturer.production-costs.store') }}" class="max-w-lg bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Product</label>
            <select name="item_id" class="w-full border rounded p-2" required>
                <option value="">Select a product</option>
                @foreach(App\Models\Item::where('type', 'finished_product')->get() as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Amount</label>
            <input type="number" name="amount" class="w-full border rounded p-2" min="0" step="0.01" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Date</label>
            <input type="date" name="date" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" class="w-full border rounded p-2"></textarea>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Add Cost</button>
    </form>
</div>
@endsection 