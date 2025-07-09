<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!--
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --text-light: #ffffff;
        }

        body {
            background-image: url('/images/showroom.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .form-container {
            background: rgba(248, 250, 252, 0.95) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .role-btn {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 2px solid rgba(203, 213, 225, 0.5) !important;
            color: #1e293b !important;
            backdrop-filter: blur(5px);
        }

        .role-btn:hover {
            background: rgba(255, 255, 255, 1) !important;
            border-color: var(--primary-color) !important;
            color: #1e293b !important;
        }

        .role-btn.admin {
            border-color: rgba(99, 102, 241, 0.5) !important;
        }

        .role-btn.admin:hover {
            border-color: #6366f1 !important;
            background: rgba(99, 102, 241, 0.1) !important;
            color: #6366f1 !important;
        }

        .role-btn.supplier {
            border-color: rgba(34, 197, 94, 0.5) !important;
        }

        .role-btn.supplier:hover {
            border-color: #22c55e !important;
            background: rgba(34, 197, 94, 0.1) !important;
            color: #22c55e !important;
        }

        .role-btn.manufacturer {
            border-color: rgba(59, 130, 246, 0.5) !important;
        }

        .role-btn.manufacturer:hover {
            border-color: #3b82f6 !important;
            background: rgba(59, 130, 246, 0.1) !important;
            color: #3b82f6 !important;
        }

        .role-btn.wholesaler {
            border-color: rgba(168, 85, 247, 0.5) !important;
        }

        .role-btn.wholesaler:hover {
            border-color: #a855f7 !important;
            background: rgba(168, 85, 247, 0.1) !important;
            color: #a855f7 !important;
        }

        .divider-text {
            background: rgba(248, 250, 252, 0.95) !important;
            color: #374151 !important;
        }

        .login-btn {
            background: transparent !important;
            color: var(--primary-color) !important;
            border: 2px solid var(--primary-color) !important;
        }

        .login-btn:hover {
            background: var(--primary-color) !important;
            color: white !important;
        }
    </style>
</head>
<body class="bg-cover bg-center bg-fixed" style="background-image: linear-gradient(135deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.3) 100%), url('{{ asset('images/showroom.png') }}');">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center mb-8">
                <div style="background: linear-gradient(rgba(255,255,255,0.7), rgba(255,255,255,0.7)), url('/images/silk.jpeg') center center/cover no-repeat; padding: 0.75rem; border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: inline-block;">
                    <img src="{{ asset('images/CA-WORD.png') }}" alt="ChicAura Logo" class="h-24 w-auto drop-shadow-lg">
                </div>
            </div>
            <h2 class="mt-6 text-center text-4xl font-bold text-white drop-shadow-lg">
                Join ChicAura
            </h2>
            <p class="mt-2 text-center text-lg text-gray-200">
                Choose your role in the fashion supply chain
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="form-container py-8 px-4 shadow-2xl sm:rounded-xl sm:px-10">
                <div class="space-y-4">
                    
                    <a href="{{ route('register.supplier') }}" class="role-btn supplier w-full flex justify-center py-3 px-4 rounded-lg shadow-sm text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                        Register as Supplier
                    </a>

                    <a href="{{ route('register.manufacturer') }}" class="role-btn manufacturer w-full flex justify-center py-3 px-4 rounded-lg shadow-sm text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Register as Manufacturer
                    </a>

                    <a href="{{ route('register.wholesaler') }}" class="role-btn wholesaler w-full flex justify-center py-3 px-4 rounded-lg shadow-sm text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                        Register as Wholesaler
                    </a>
                </div>

                <div class="mt-8">
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
                        <a href="{{ route('login') }}" class="login-btn w-full flex justify-center py-3 px-4 rounded-lg shadow-sm text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                            Sign in to your account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
-->


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
            background: rgba(248, 250, 252, 0.95) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-input {
            background: rgba(0, 0, 0, 0.1) !important;
            border: 2px solid rgb(0, 0, 0) !important;
            color: #1e293b  !important;
            backdrop-filter: blur(5px);
        }

        .form-input::placeholder {
            color: #1e293b !important;
        }

        .form-input:focus {
            background: rgba(255, 255, 255, 0.2) !important;
            border-color: #615fff !important;
            color: rgb(0, 0, 0) !important;
        }

        .form-input2 {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 2px solid rgb(0, 0, 0) !important;
            color: #1e293b  !important;
            backdrop-filter: blur(5px);
        }

        .form-input2::placeholder {
            color: #1e293b !important;
        }

        .form-input2:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            border-color: #615fff !important;
            color:  #1e293b !important;
        }

        select.form-input option {
            background-color: rgba(0, 0, 0, 0.7);
            color: var(--text-light); 
}

        .form-label {
            color:  #1e293b  !important;
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
        .dynamic-section {
          opacity: 0;
          transform: translateY(10px);
          transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .dynamic-section.show {
          opacity: 1;
          transform: translateY(0);
        }

    </style>
</head>
<body class="bg-cover bg-center bg-fixed" style="background-image: linear-gradient(135deg,rgba(0, 0, 0, 0.2),rgba(0, 0, 0, 0.4) 100%), url('{{ asset('images/showroom.png') }}');">
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
                <form class="space-y-6" action="{{ route('register.newUser') }}" method="POST" enctype="multipart/form-data">
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
                                <option value="supplier">Supplier</option>
                                <option value="manufacturer">Manufacturer</option>
                                <option value="wholesaler">Wholesaler</option>
                            </select>
                        </div>
                                
                    </div>
                    
                  <!-- Dynamic Fields -->
                    <div id="dynamicFields" class="dynamic-section space-y-6"></div>

                    
                </form>
            </div>
        </div>
    </div>
    <script>
    const roleSelect = document.getElementById('role');
    const dynamicFields = document.getElementById('dynamicFields');

    const fieldTemplates = {
      admin: `
        <div>
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Register User
            </button>
        </div>
      `,
      supplier: `
        <div>
            <label for="business_address" class="form-label block text-sm font-medium">Business Address</label>
            <div class="mt-1">
                <textarea id="business_address" name="business_address" rows="3" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
        </div>

        <div>
            <label for="phone" class="form-label block text-sm font-medium">Business Phone</label>
            <div class="mt-1">
                <input id="phone" name="phone" type="tel" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>

        <div>
            <label for="license_document" class="form-label block text-sm font-medium">Business License</label>
            <div class="mt-1 grid grid-cols-1 lg:grid-cols-5 gap-1">
                <input id="license_document" name="license_document" type="file" required class="form-input w-full lg:col-span-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <a href="{{asset('templates/SUPPLIER APPLICATION FORM.docx') }}" class="w-full lg:col-span-1 flex justify-center px-4 py-3 mt-1 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 outline-none transition duration-200 transform hover:scale-105 cursor-pointer"><i class="fa-solid fa-file-arrow-down text-3xl"></i></a>
            </div>
        </div>

        <div>
            <label for="materials_supplied" class="form-label block text-sm font-medium">Materials Supplied</label>
            <div class="mt-1">
                <div id="materials_supplied" class="form-input2 grid grid-cols-1 lg:grid-cols-3 gap-6 block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 outline-none hover:ring-2 hover:ring-indigo-500 hover:border-indigo-500 sm:text-sm">
                    <div class="flex items-center"><input type="checkbox" name="materials_supplied[]" value="fabric" class="rounded-[4px]"><label class="ml-1">Fabric</label></div>
                    <div class="flex items-center"><input type="checkbox" name="materials_supplied[]" value="thread" class="rounded-[4px]"><label class="ml-1">Thread</label></div>
                    <div class="flex items-center"><input type="checkbox" name="materials_supplied[]" value="button" class="rounded-[4px]"><label class="ml-1">Buttons</label></div>
                    <div class="flex items-center"><input type="checkbox" name="materials_supplied[]" value="zippers" class="rounded-[4px]"><label class="ml-1">Zippers</label></div>
                    <div class="flex items-center"><input type="checkbox" name="materials_supplied[]" value="dyes" class="rounded-[4px]"><label class="ml-1">Dyes</label></div>
                    <div class="flex items-center"><input type="checkbox" name="materials_supplied[]" value="other" class="rounded-[4px]"><label class="ml-1">Other</label></div>
                </div>
            </div>
        </div>
        <div>
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Register User
            </button>
        </div>
        `,
      manufacturer: `
        <div> 
            <label for="business_address" class="form-label block text-sm font-semibold mb-2">Factory Address</label>
            <textarea id="business_address" name="business_address" rows="3" required class="form-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter your complete factory address"></textarea>
        </div>

        <div>
            <label for="phone" class="form-label block text-sm font-semibold mb-2">Business Phone</label>
            <input id="phone" name="phone" type="tel" required class="form-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter your business phone number">
        </div>

                    
        <div>
            <label for="license_document" class="form-label block text-sm font-semibold mb-2">Manufacturing License</label>
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-1">
                <input id="license_document" name="license_document" type="file" required class="form-input lg:col-span-4 w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <a href="{{asset('templates/MANUFACTURER APPLICATION FORM.docx') }}" class="w-full lg:col-span-1 flex justify-center px-4 py-3 mt-1 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 outline-none transition duration-200 transform hover:scale-105 cursor-pointer"><i class="fa-solid fa-file-arrow-down text-3xl"></i></a>
            </div>        
        </div>

        <div>
            <label for="production_capacity" class="form-label block text-sm font-semibold mb-2">Monthly Production Capacity (units)</label>
            <input id="production_capacity" name="production_capacity" type="number" required class="form-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter monthly production capacity">
        </div>
        <div>
            <label for="specialization" class="form-label block text-sm font-semibold mb-2">Specialization</label>
            <div id="specialization"multiple required class="form-input2  grid grid-cols-1 lg:grid-cols-2 gap-6 w-full px-4 py-3 rounded-lg hover:ring-2 hover:ring-blue-500 transition duration-200">
                    <div class="flex items-center"><input type="checkbox" name="specialization[]" value="casual_wear" class="rounded-[4px]"><label class="ml-1">Casual Wear</label></div>
                    <div class="flex items-center"><input type="checkbox" name="specialization[]" value="formal_wear" class="rounded-[4px]"><label class="ml-1">Formal Wear</label></div>
                    <div class="flex items-center"><input type="checkbox" name="specialization[]" value="sports_wear" class="rounded-[4px]"><label class="ml-1">Sports Wear</label></div>
                    <div class="flex items-center"><input type="checkbox" name="specialization[]" value="evening_wear" class="rounded-[4px]"><label class="ml-1">Evening Wear</label></div>
                    <div class="flex items-center"><input type="checkbox" name="specialization[]" value="accessories" class="rounded-[4px]"><label class="ml-1">Accessories</label></div>
                    <div class="flex items-center"><input type="checkbox" name="specialization[]" value="other" class="rounded-[4px]"><label class="ml-1">Other</label></div>
            </div>
        </div>
        <div>
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Register User
            </button>
        </div>
      `,
      wholesaler: `
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
                <a href="{{asset('templates/WHOLESALER APPLICATION FORM.docx') }}" class="w-full lg:col-span-1 flex justify-center px-4 py-3 mt-1 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 outline-none transition duration-200 transform hover:scale-105 cursor-pointer"><i class="fa-solid fa-file-arrow-down text-3xl"></i></a>
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
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Register User
            </button>
        </div>     
      `
    };

    function updateDynamicFields(role) {
      // Hide current fields
      dynamicFields.classList.remove('show');

      // Wait for transition then update content
      setTimeout(() => {
        dynamicFields.innerHTML = fieldTemplates[role] || '';
        dynamicFields.classList.add('show');
      }, 100); // Delay allows fade-out before new content
    }

    roleSelect.addEventListener('change', (e) => {
      updateDynamicFields(e.target.value);
    });

    // Initialize on page load
    updateDynamicFields(roleSelect.value);
  </script>
</body>
</html>