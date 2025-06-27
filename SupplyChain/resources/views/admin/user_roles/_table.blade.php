<div id="user-roles-table">
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="py-2 px-4">Name</th>
                <th class="py-2 px-4">Email</th>
                <th class="py-2 px-4">Role</th>
                <th class="py-2 px-4">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <form class="role-update-form" data-user-id="{{ $user->id }}">
                    @csrf
                    <td class="py-2 px-4">{{ $user->name }}</td>
                    <td class="py-2 px-4">{{ $user->email }}</td>
                    <td class="py-2 px-4">
                        <select name="role" class="border rounded p-1">
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="supplier" {{ $user->role == 'supplier' ? 'selected' : '' }}>Supplier</option>
                            <option value="manufacturer" {{ $user->role == 'manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                            <option value="wholesaler" {{ $user->role == 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                        </select>
                    </td>
                    <td class="py-2 px-4">
                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Update</button>
                    </td>
                </form>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
