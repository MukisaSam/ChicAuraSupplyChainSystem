@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold text-white mb-4">Create Production Schedule</h1>
    <form method="POST" action="{{ route('manufacturer.production-schedules.store') }}" class="max-w-lg bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Product</label>
            <select name="product_id" class="w-full border rounded p-2" required>
                <option value="">Select a product</option>
                @foreach(App\Models\Item::where('type', 'finished_product')->get() as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Start Date</label>
            <input type="date" name="start_date" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">End Date</label>
            <input type="date" name="end_date" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Status</label>
            <select name="status" class="w-full border rounded p-2" required>
                <option value="planned">Planned</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Create Schedule</button>
    </form>
</div>
@endsection 