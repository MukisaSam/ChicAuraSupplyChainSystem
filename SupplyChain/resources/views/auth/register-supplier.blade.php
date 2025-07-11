<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChicAura - Supplier Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --text-light: #ffffff;
        }

        body {
            background-image: url('/images/supplier.jpg');
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

        .submit-btn {
            background: linear-gradient(135deg, #22c55e, #16a34a) !important;
            color: var(--text-light) !important;
            border: none !important;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #16a34a, #22c55e) !important;
        }
    </style>
</head>
<body class="bg-cover bg-center bg-fixed" style="background-image: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%), url('{{ asset('images/supplier.jpg') }}');">

    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white drop-shadow-lg">
                Supplier Registration
            </h2>
            <p class="mt-2 text-center text-sm text-white drop-shadow-lg">
                Join our network of trusted suppliers and connect with leading manufacturers in the fashion industry
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="form-container py-8 px-4 shadow-2xl sm:rounded-xl sm:px-10">
                <form class="space-y-6" action="{{ route('register.supplier.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="role" value="supplier">

                    <div>
                        <label for="name" class="form-label block text-sm font-medium">Company Name</label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="form-label block text-sm font-medium">Business Email</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="form-label block text-sm font-medium">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label block text-sm font-medium">Confirm Password</label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

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
                            <a href="{{asset('templates/SUPPLIER APPLICATION FORM.docx') }}" class="w-full lg:col-span-1 flex justify-center px-4 py-3 mt-1 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-green-600 hover:bg-green-700 outline-none transition duration-200 transform hover:scale-105 cursor-pointer"><i class="fa-solid fa-file-arrow-down text-3xl"></i></a>
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
                        <button type="submit" class="submit-btn w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 transform hover:scale-105">
                            Register as Supplier
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
                        <a href="{{ route('login') }}" class="login-btn w-full flex justify-center py-2 px-4 rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Sign in
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
