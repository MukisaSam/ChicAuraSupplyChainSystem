@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Edit Production Cost</h1>
        <form method="POST" action="{{ route('manufacturer.production-costs.update', $productionCost->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Product</label>
                <select name="item_id" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="">Select a product</option>
                    @foreach(App\Models\Item::where('type', 'finished_product')->get() as $product)
                        <option value="{{ $product->id }}" @if($product->id == $productionCost->item_id) selected @endif>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Amount</label>
                <input type="number" name="amount" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" min="0" step="0.01" value="{{ $productionCost->amount }}" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Date</label>
                <input type="date" name="date" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" value="{{ $productionCost->date }}" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Notes</label>
                <textarea name="notes" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">{{ $productionCost->notes }}</textarea>
            </div>
            <div class="flex justify-between mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">Update Cost</button>
                <a href="{{ route('manufacturer.production-costs.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.production-costs.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to Costs</a>
</div>
@endsection 