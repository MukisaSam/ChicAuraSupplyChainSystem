<x-modal name="profile-editor-modal" :show="$show ?? false" maxWidth="lg">
    <div class="p-6">
        <h2 class="text-lg font-bold mb-4">Edit Profile</h2>
        <form method="post" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="flex items-center space-x-4">
                <img id="profile-modal-preview" class="h-16 w-16 rounded-full object-cover border-2 border-gray-300" src="{{ Auth::user()->profile_picture ? Storage::disk('public')->url(Auth::user()->profile_picture) : asset('images/default-avatar.svg') }}" alt="Profile Picture">
                <div>
                    <label for="modal_profile_picture" class="block text-sm font-medium">Profile Picture</label>
                    <input type="file" id="modal_profile_picture" name="profile_picture" accept="image/*" class="block w-full text-sm text-gray-500" onchange="modalPreviewImage(this)">
                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF up to 2MB</p>
                </div>
            </div>
            <div>
                <label for="modal_name" class="block text-sm font-medium">Full Name</label>
                <input id="modal_name" name="name" type="text" class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ old('name', Auth::user()->name) }}" required autofocus autocomplete="name">
                @error('name') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="modal_email" class="block text-sm font-medium">Email</label>
                <input id="modal_email" name="email" type="email" class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ old('email', Auth::user()->email) }}" required autocomplete="username">
                @error('email') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-700" x-on:click="$dispatch('close')">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Save</button>
            </div>
        </form>
    </div>
    <script>
        function modalPreviewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-modal-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-modal> 