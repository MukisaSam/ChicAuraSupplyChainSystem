@extends('layouts.admin-dashboard')

@section('title', 'Audit Logs')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Audit Logs</h1>

    <div class="overflow-x-auto">
        <table class="w-full bg-white rounded-2xl shadow text-sm">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-3">Date</th>
                    <th class="p-3">User</th>
                    <th class="p-3">Action</th>
                    <th class="p-3">Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                <tr class="border-t">
                    <td class="p-3">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                    <td class="p-3">{{ $log->user->name }}</td>
                    <td class="p-3">{{ $log->action }}</td>
                    <td class="p-3">{{ $log->details }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
