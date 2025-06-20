<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChicAura - Admin Registration</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --text-light: #ffffff;
        }

        body {
            background-image: url('/images/black.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
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

        select.form-input option {
            background-color: rgba(0, 0, 0, 0.7);
            color: var(--text-light); 
}

        .form-label {
            color: var(--text-light) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .divider-text {
            background: rgba(255, 255, 255, 0.1) !important;
            color: var(--text-light) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .login-btn {
            background: transparent !important;
            color: var(--text-light) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
        }

        .login-btn:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: var(--primary-color) !important;
        }
    </style>
</head>
<body class="bg-cover bg-center bg-fixed" style="background-image: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%), url('{{ asset('images/black.jpeg') }}');">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="h-24 w-auto drop-shadow-lg">
            </div>
            <h2 class="mt-6 text-center text-4xl font-bold text-white drop-shadow-lg">
                User Registration
            </h2>
            <p class="mt-2 text-center text-lg text-gray-200">
                Join our elite team and help manage the ChicAura supply chain ecosystem
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="form-container py-8 px-4 shadow-2xl sm:rounded-xl sm:px-10">
                <form class="space-y-6" action="{{ route('register.admin.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role" value="admin">

                    <div>
                        <label for="name" class="form-label block text-sm font-medium">Full Name</label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="form-label block text-sm font-medium">Email address</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="form-label block text-sm font-medium">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="admin_code" class="form-label block text-sm font-medium">Role</label>
                        <div class="mt-1">
                            <select id="role" name="role" class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="admin">Administrator</option>
                                <option value="supplier">Supplier</option>
                                <option value="manufacturer">Manufacturer</option>
                                <option value="wholesaler">Wholesaler</option>
                            </select>
                        </div>
                                
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Register User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
