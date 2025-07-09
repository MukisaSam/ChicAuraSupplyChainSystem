@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">BoM Details</h1>
    <div class="mb-4">
        <strong>Product:</strong> {{ $billOfMaterial->product->name ?? '-' }}
    </div>
    <div class="mb-4">
        <strong>Components:</strong>
        <table class="min-w-full table-auto bg-white rounded shadow mt-2">
            <thead>
                <tr>
                    <th class="px-4 py-2">Raw Material</th>
                    <th class="px-4 py-2">Quantity</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($billOfMaterial->components as $component)
                <tr>
                    <td class="border px-4 py-2">
                        @if(request('edit_component') == $component->id)
                            <form method="POST" action="{{ route('manufacturer.bom.update-component', [$billOfMaterial->id, $component->id]) }}" class="flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                <select name="raw_item_id" class="border rounded p-2" required>
                                    <option value="">Select raw material</option>
                                    @foreach(App\Models\Item::where('type', 'raw_material')->get() as $rawMaterial)
                                        <option value="{{ $rawMaterial->id }}" @if($rawMaterial->id == $component->raw_item_id) selected @endif>{{ $rawMaterial->name }}</option>
                                    @endforeach
                                </select>
                        @else
                            {{ $component->rawItem->name ?? '-' }}
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        @if(request('edit_component') == $component->id)
                                <input type="number" name="quantity" class="border rounded p-2 w-24" min="1" value="{{ $component->quantity }}" required>
                        @else
                            {{ $component->quantity }}
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        @if(request('edit_component') == $component->id)
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Save</button>
                                <a href="{{ route('manufacturer.bom.show', $billOfMaterial->id) }}" class="ml-2 text-gray-600">Cancel</a>
                            </form>
                        @else
                            <a href="{{ route('manufacturer.bom.show', [$billOfMaterial->id, 'edit_component' => $component->id]) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                            <form action="{{ route('manufacturer.bom.delete-component', [$billOfMaterial->id, $component->id]) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline bg-transparent border-0 p-0 m-0 cursor-pointer">Remove</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-gray-400 py-4">No components found.</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mb-4 mt-8">
        <h2 class="text-lg font-semibold mb-2">Add New Component</h2>
        <form method="POST" action="{{ route('manufacturer.bom.add-component', $billOfMaterial->id) }}" class="flex items-center space-x-2">
            @csrf
            <select name="raw_item_id" class="border rounded p-2" required>
                <option value="">Select raw material</option>
                @foreach(App\Models\Item::where('type', 'raw_material')->get() as $rawMaterial)
                    <option value="{{ $rawMaterial->id }}">{{ $rawMaterial->name }}</option>
                @endforeach
            </select>
            <input type="number" name="quantity" class="border rounded p-2 w-24" min="1" placeholder="Qty" required>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Add</button>
        </form>
    </div>
    <a href="{{ route('manufacturer.bom.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Back to List</a>
</div>
@endsection 