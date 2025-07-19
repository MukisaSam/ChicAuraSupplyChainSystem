@php use Illuminate\Support\Str; @endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Profile') }}
            </h2>
            <button data-theme-toggle class="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 rounded-full transition-colors" title="Switch Theme">
                <i class="fas fa-moon text-lg"></i>
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Picture and Basic Info -->
            <div class="form-container p-4 sm:p-8 shadow-2xl sm:rounded-xl">
                <div class="max-w-2xl">
                    <header>
                        <h2 class="form-header text-lg font-medium">
                            {{ __('Profile Information') }}
                        </h2>
                        <p class="form-description mt-1 text-sm">
                            {{ __("Update your account's profile information and profile picture.") }}
                        </p>
                    </header>

                    <form method="post" action="{{ route('user.profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                        @csrf

                        <!-- Profile Picture Section -->
                        <div class="flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                    <img id="profile-preview" 
                                     class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-lg" 
                                     src="{{ $user->profile_picture ? (Str::startsWith($user->profile_picture, ['http://', 'https://']) ? $user->profile_picture : Storage::disk('public')->url($user->profile_picture)) : asset('images/default-avatar.svg') }}" 
                                     alt="Profile Picture">
                            </div>
                            <div class="flex-1">
                                <label for="profile_picture" class="form-label block text-sm font-medium">
                                    {{ __('Profile Picture') }}
                                </label>
                                <input type="file" 
                                       id="profile_picture" 
                                       name="profile_picture" 
                                       accept="image/*"
                                       class="form-input mt-1 block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       onchange="previewImage(this)">
                                <p class="form-description text-xs mt-1">PNG, JPG, GIF up to 2MB</p>
                                <label for="profile_picture_url" class="form-label block text-sm font-medium mt-2">
                                    {{ __('Or use an external image URL') }}
                                </label>
                                <input type="url" id="profile_picture_url" name="profile_picture_url" class="form-input mt-1 block w-full text-sm" placeholder="https://example.com/image.jpg" value="{{ old('profile_picture_url', (Str::startsWith($user->profile_picture, ['http://', 'https://']) ? $user->profile_picture : '') ) }}" oninput="previewImageUrl(this)">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Full Name')" class="form-label" />
                                <x-text-input id="name" name="name" type="text" class="form-input mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" class="form-label" />
                                <x-text-input id="email" name="email" type="email" class="form-input mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>
                        </div>

                        @if($roleData)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="phone" :value="__('Phone Number')" class="form-label" />
                                <x-text-input id="phone" name="phone" type="tel" class="form-input mt-1 block w-full" :value="old('phone', $roleData->phone ?? '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="address" :value="__('Business Address')" class="form-label" />
                            <textarea id="address" name="address" rows="3" class="form-input mt-1 block w-full" placeholder="Enter your business address">{{ old('address', $roleData->business_address ?? '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>
                        @endif

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

                            @if (session('success'))
                                <p class="text-sm text-green-300">{{ session('success') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Update -->
            <div class="form-container p-4 sm:p-8 shadow-2xl sm:rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="form-container p-4 sm:p-8 shadow-2xl sm:rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        function previewImageUrl(input) {
            if (input.value) {
                document.getElementById('profile-preview').src = input.value;
            } else {
                // fallback to default or uploaded image
            }
        }
    </script>

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --text-light: #ffffff;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-input {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            color: var(--text-light) !important;
            backdrop-filter: blur(5px);
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .form-input:focus {
            background: rgba(255, 255, 255, 0.2) !important;
            border-color: var(--primary-color) !important;
            color: var(--text-light) !important;
        }

        .form-label {
            color: var(--text-light) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .form-header {
            color: var(--text-light) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .form-description {
            color: rgba(255, 255, 255, 0.8) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
    </style>
</x-app-layout>
