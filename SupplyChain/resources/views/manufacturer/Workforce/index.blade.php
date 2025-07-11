@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-white">Workforce Management</h1>
    <div class="flex justify-end mb-6">
        <a href="{{ route('manufacturer.workforce.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">Add Employee</a>
    </div>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Full Name</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Email</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Job Role</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr class="even:bg-gray-50 hover:bg-indigo-50 transition">
                    <td class="border-t px-4 py-2">{{ $employee->fullname }}</td>
                    <td class="border-t px-4 py-2">{{ $employee->email }}</td>
                    <td class="border-t px-4 py-2">{{ $employee->job_role }}</td>
                    <td class="border-t px-4 py-2">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($employee->status == 'Active') bg-green-100 text-green-800
                            @elseif($employee->status == 'Inactive') bg-gray-200 text-gray-700
                            @elseif($employee->status == 'On Leave') bg-yellow-100 text-yellow-800
                            @elseif($employee->status == 'Terminated') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $employee->status }}
                        </span>
                    </td>
                    <td class="border-t px-4 py-2">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('manufacturer.workforce.show', $employee) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-3 rounded-lg shadow transition">View</a>
                            <a href="{{ route('manufacturer.workforce.edit', $employee) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-1 px-3 rounded-lg shadow transition">Edit</a>
                            <form action="{{ route('manufacturer.workforce.destroy', $employee) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-red-400">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-400 py-8">No employees found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex justify-center">
        {{ $employees->links() }}
    </div>
</div>
@endsection 