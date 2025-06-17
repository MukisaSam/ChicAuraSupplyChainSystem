<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChicAura - Wholesaler Registration</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" class="bg-cover bg-center" style="background-image: url('{{ asset('images/manequin.jpeg') }}');">
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Wholesaler Registration
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Join our network of premium wholesalers and access quality fashion products from trusted manufacturers
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" action="{{ route('register.wholesaler.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="role" value="wholesaler">

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Company Name</label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Business Email</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="business_address" class="block text-sm font-medium text-gray-700">Business Address</label>
                        <div class="mt-1">
                            <textarea id="business_address" name="business_address" rows="3" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Business Phone</label>
                        <div class="mt-1">
                            <input id="phone" name="phone" type="tel" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="license_document" class="block text-sm font-medium text-gray-700">Business License</label>
                        <div class="mt-1">
                            <input id="license_document" name="license_document" type="file" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="business_type" class="block text-sm font-medium text-gray-700">Business Type</label>
                        <div class="mt-1">
                            <select id="business_type" name="business_type" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="retail_chain">Retail Chain</option>
                                <option value="boutique">Boutique</option>
                                <option value="department_store">Department Store</option>
                                <option value="online_retailer">Online Retailer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="preferred_categories" class="block text-sm font-medium text-gray-700">Preferred Categories</label>
                        <div class="mt-1">
                            <select id="preferred_categories" name="preferred_categories[]" multiple required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="casual_wear">Casual Wear</option>
                                <option value="formal_wear">Formal Wear</option>
                                <option value="sports_wear">Sports Wear</option>
                                <option value="evening_wear">Evening Wear</option>
                                <option value="accessories">Accessories</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="monthly_order_volume" class="block text-sm font-medium text-gray-700">Expected Monthly Order Volume (units)</label>
                        <div class="mt-1">
                            <input id="monthly_order_volume" name="monthly_order_volume" type="number" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Register as Wholesaler
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">
                                Already have an account?
                            </span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('login') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-purple-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Sign in
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
