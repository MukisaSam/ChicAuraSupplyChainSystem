<table class="w-full bg-white rounded-2xl shadow text-sm">
    <thead>
        <tr class="bg-gray-100 text-left">
            <th class="p-3">Name</th>
            <th class="p-3">Email</th>
            <th class="p-3">Role</th>
            <th class="p-3">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr class="border-t">
            <td class="p-3">{{ $user->name }}</td>
            <td class="p-3">{{ $user->email }}</td>
            <td class="p-3">{{ ucfirst($user->role) }}</td>
            <td class="p-3">
                <form action="{{ route('admin.user_roles.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="role" class="rounded border-gray-300">
                        @foreach($roles as $role)
                            <option value="{{ $role }}" @if($user->role === $role) selected @endif>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Update</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table> 