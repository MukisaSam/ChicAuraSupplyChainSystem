<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChicAura - Wholesaler Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --text-light: #ffffff;
        }

        body {
            background-image: url('/images/wholesaler.jpg');
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
            border-color: #2b7fff !important;
            color: var(--text-light) !important;
        }

        .form-input2 {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            color: var(--text-light) !important;
            backdrop-filter: blur(5px);
        }

        .form-input2::placeholder {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .form-input2:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            border-color: #2b7fff !important;
            color: var(--text-light) !important;
        }

        .form-label {
            color: var(--text-light) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        select.form-input option {
            background-color: rgba(0, 0, 0, 0.7);
            color: var(--text-light);
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
<body class="bg-cover bg-center bg-fixed" style="background-image: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%), url('{{ asset('images/wholesaler.jpg') }}');">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="h-24 w-auto drop-shadow-lg">
            </div>
            <h2 class="mt-6 text-center text-4xl font-bold text-white drop-shadow-lg">
                Wholesaler Registration
            </h2>
            <p class="mt-2 text-center text-lg text-gray-200">
                Join our network of premium wholesalers and access quality fashion products from trusted manufacturers
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="form-container py-8 px-4 shadow-2xl sm:rounded-xl sm:px-10">
                <form class="space-y-6" action="{{ route('register.wholesaler.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="role" value="wholesaler">

                    <div>
                        <label for="name" class="form-label block text-sm font-medium">Company Name</label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="form-label block text-sm font-medium">Business Email</label>
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
                        <label for="password_confirmation" class="form-label block text-sm font-medium">Confirm Password</label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="business_address" class="form-label block text-sm font-medium">Business Address</label>
                        <div class="mt-1">
                            <textarea id="business_address" name="business_address" rows="3" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="form-label block text-sm font-medium">Business Phone</label>
                        <div class="mt-1">
                            <input id="phone" name="phone" type="tel" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="license_document" class="form-label block text-sm font-medium">Business License</label>
                        <div class="mt-1 grid grid-cols-1 lg:grid-cols-5 gap-1">
                            <input id="license_document" name="license_document" type="file" required class="form-input w-full lg:col-span-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <a href="{{asset('templates/WHOLESALER APPLICATION FORM.docx') }}" class="w-full lg:col-span-1 flex justify-center px-4 py-3 mt-1 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-purple-600 hover:bg-purple-700 outline-none transition duration-200 transform hover:scale-105 cursor-pointer"><i class="fa-solid fa-file-arrow-down text-3xl"></i></a>
                        </div>
                    </div>

                    <div>
                        <label for="business_type" class="form-label block text-sm font-medium">Business Type</label>
                        <div class="mt-1">
                            <select id="business_type" name="business_type" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="retail_chain">Retail Chain</option>
                                <option value="boutique">Boutique</option>
                                <option value="department_store">Department Store</option>
                                <option value="online_retailer">Online Retailer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="preferred_categories" class="form-label block text-sm font-semibold mb-2">Specialization</label>
                        <div id="preferred_categories"multiple required class="form-input2  grid grid-cols-1 lg:grid-cols-2 gap-6 w-full px-4 py-3 rounded-lg hover:ring-2 hover:ring-blue-500 transition duration-200">
                                <div class="flex items-center"><input type="checkbox" name="preferred_categories[]" value="casual_wear" class="rounded-[4px]"><label class="ml-1">Casual Wear</label></div>
                                <div class="flex items-center"><input type="checkbox" name="preferred_categories[]" value="formal_wear" class="rounded-[4px]"><label class="ml-1">Formal Wear</label></div>
                                <div class="flex items-center"><input type="checkbox" name="preferred_categories[]" value="sports_wear" class="rounded-[4px]"><label class="ml-1">Sports Wear</label></div>
                                <div class="flex items-center"><input type="checkbox" name="preferred_categories[]" value="evening_wear" class="rounded-[4px]"><label class="ml-1">Evening Wear</label></div>
                                <div class="flex items-center"><input type="checkbox" name="preferred_categories[]" value="accessories" class="rounded-[4px]"><label class="ml-1">Accessories</label></div>
                                <div class="flex items-center"><input type="checkbox" name="preferred_categories[]" value="other" class="rounded-[4px]"><label class="ml-1">Other</label></div>
                        </div>
                    </div>
                    <div>
                        <label for="monthly_order_volume" class="form-label block text-sm font-medium">Expected Monthly Order Volume (units)</label>
                        <div class="mt-1">
                            <input id="monthly_order_volume" name="monthly_order_volume" type="number" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 transform hover:scale-105">
                            Register as Wholesaler
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="divider-text px-2">
                                Already have an account?
                            </span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('login') }}" class="login-btn w-full flex justify-center py-2 px-4 rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Sign in
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
