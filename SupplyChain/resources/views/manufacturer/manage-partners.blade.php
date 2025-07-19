@extends('manufacturer.layouts.dashboard')

@section('content')
<div class="container mx-auto py-10">
  <div class="bg-white rounded-xl shadow-lg p-8">
    <h1 class="text-3xl font-bold text-black mb-10 text-center">Manage Partners</h1>
    <div x-data="{ tab: 'suppliers' }" class="space-y-10">
        <div class="flex justify-center mb-6">
            <button @click="tab = 'suppliers'" :class="tab === 'suppliers' ? 'bg-indigo-600 text-white' : 'bg-white text-indigo-600'" class="px-6 py-2 rounded-l-lg font-semibold border border-indigo-600 focus:outline-none transition">Suppliers</button>
            <button @click="tab = 'wholesalers'" :class="tab === 'wholesalers' ? 'bg-indigo-600 text-white' : 'bg-white text-indigo-600'" class="px-6 py-2 rounded-r-lg font-semibold border-t border-b border-r border-indigo-600 focus:outline-none transition">Wholesalers</button>
        </div>
        <!-- Suppliers Card -->
        <div x-show="tab === 'suppliers'" class="bg-white rounded-xl shadow-lg p-6 flex flex-col">
            <h2 class="text-2xl font-semibold text-black mb-6 text-center">Suppliers</h2>
            <div class="overflow-x-auto rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Name</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Email</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Phone</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $supplier)
                        <tr class="even:bg-gray-50 hover:bg-indigo-50 transition">
                            <td class="px-4 py-2 border-b">{{ $supplier->user->name ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $supplier->user->email ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $supplier->phone ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">
                                <a href="{{ route('manufacturer.chat.show', ['contactId' => $supplier->user_id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-4 rounded-lg shadow transition">Message</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Wholesalers Card -->
        <div x-show="tab === 'wholesalers'" class="bg-white rounded-xl shadow-lg p-6 flex flex-col">
            <h2 class="text-2xl font-semibold text-black mb-6 text-center">Wholesalers</h2>
            <div class="overflow-x-auto rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Name</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Email</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Phone</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wholesalers as $wholesaler)
                        <tr class="even:bg-gray-50 hover:bg-indigo-50 transition">
                            <td class="px-4 py-2 border-b">{{ $wholesaler->user->name ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $wholesaler->user->email ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $wholesaler->phone ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">
                                <a href="{{ route('manufacturer.chat.show', ['contactId' => $wholesaler->user_id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-4 rounded-lg shadow transition">Message</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection 