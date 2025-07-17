@extends('layouts.admin-dashboard')

@section('title', 'User Roles')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">User Roles</h1>
    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    @include('admin.user_roles._table', ['users' => $users, 'roles' => $roles])
</div>
@endsection 