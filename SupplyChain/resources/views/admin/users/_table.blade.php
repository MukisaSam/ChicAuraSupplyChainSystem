<div id="user-management-table">
    <div class="mb-4 flex justify-between">
        <button id="add-user-btn" class="bg-blue-500 text-white px-4 py-2 rounded">Add User</button>
        <input type="text" id="user-search" class="border rounded p-2" placeholder="Search users...">
    </div>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="py-2 px-4">Name</th>
                <th class="py-2 px-4">Email</th>
                <th class="py-2 px-4">Role</th>
                <th class="py-2 px-4">Status</th>
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="py-2 px-4">{{ $user->name }}</td>
                <td class="py-2 px-4">{{ $user->email }}</td>
                <td class="py-2 px-4">{{ ucfirst($user->role) }}</td>
                <td class="py-2 px-4">{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                <td class="py-2 px-4">
                    <button class="view-user-btn text-blue-500" data-id="{{ $user->id }}">View</button>
                    <button class="edit-user-btn text-yellow-500" data-id="{{ $user->id }}">Edit</button>
                    <button class="delete-user-btn text-red-500" data-id="{{ $user->id }}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>