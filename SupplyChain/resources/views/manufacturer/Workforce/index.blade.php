@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4 text-white">Workforce Management</h1>
    <a href="{{ route('manufacturer.workforce.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">Add Employee</a>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Full Name</th>
                <th class="py-2 px-4 border-b">Email</th>
                <th class="py-2 px-4 border-b">Job Role</th>
                <th class="py-2 px-4 border-b">Status</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td class="py-2 px-4 border-b">{{ $employee->fullname }}</td>
                <td class="py-2 px-4 border-b">{{ $employee->email }}</td>
                <td class="py-2 px-4 border-b">{{ $employee->job_role }}</td>
                <td class="py-2 px-4 border-b">{{ $employee->status }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('manufacturer.workforce.edit', $employee) }}" class="text-blue-600">Edit</a>
                    <form action="{{ route('manufacturer.workforce.destroy', $employee) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $employees->links() }}</div>
</div>
@endsection 