<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="form-container p-4 sm:p-8 shadow-2xl sm:rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="form-container p-4 sm:p-8 shadow-2xl sm:rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="form-container p-4 sm:p-8 shadow-2xl sm:rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

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
