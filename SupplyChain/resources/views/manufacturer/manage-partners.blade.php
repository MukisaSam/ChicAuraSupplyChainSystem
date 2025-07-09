@extends('manufacturer.layouts.dashboard')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Manage Partners</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Suppliers Table -->
        <div>
            <h2 class="text-xl font-semibold mb-4">Suppliers</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border-b">Name</th>
                            <th class="px-4 py-2 border-b">Email</th>
                            <th class="px-4 py-2 border-b">Phone</th>
                            <th class="px-4 py-2 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $supplier)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $supplier->user->name ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $supplier->user->email ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $supplier->phone ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">
                                <a href="{{ route('manufacturer.chat.show', ['contactId' => $supplier->user_id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">Message</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Wholesalers Table -->
        <div>
            <h2 class="text-xl font-semibold mb-4">Wholesalers</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border-b">Name</th>
                            <th class="px-4 py-2 border-b">Email</th>
                            <th class="px-4 py-2 border-b">Phone</th>
                            <th class="px-4 py-2 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wholesalers as $wholesaler)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $wholesaler->user->name ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $wholesaler->user->email ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $wholesaler->phone ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">
                                <a href="{{ route('manufacturer.chat.show', ['contactId' => $wholesaler->user_id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">Message</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 