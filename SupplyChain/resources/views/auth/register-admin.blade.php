<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('login') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --text-light: #ffffff;
            --card-bg: #fff;
            --card-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        body {
            min-height: 100vh;
            background: linear-gradient(120deg, #f5f6fa 0%, #e7e5df 60%, #ececec 100%);
            font-family: 'Figtree', 'Open Sans', 'Helvetica Neue', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            background: var(--card-bg);
            box-shadow: var(--card-shadow);
            border-radius: 1.25rem;
            padding: 2.5rem 2rem;
            max-width: 400px;
            margin: 2rem auto;
            border: 1px solid #e5e7eb;
        }

        .form-input {
            background: #f1f5f9 !important;
            border: 2px solid #e5e7eb !important;
            color: #1e293b !important;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            width: 100%;
            margin-bottom: 1rem;
            transition: border-color 0.2s;
        }

        .form-input::placeholder {
            color: #94a3b8 !important;
        }

        .form-input:focus {
            background: #fff !important;
            border-color: var(--primary-color) !important;
            outline: none;
            color: #1e293b !important;
        }

        .form-label {
            color: #1e293b !important;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .checkbox-label {
            color: #374151 !important;
        }

        .forgot-link {
            color: var(--primary-color) !important;
            text-decoration: none;
        }

        .forgot-link:hover {
            color: var(--primary-dark) !important;
            text-decoration: underline;
        }

        .divider-text {
            background: #fff !important;
            color: #64748b !important;
            padding: 0 0.5rem;
        }

        .register-btn {
            background: transparent !important;
            color: var(--primary-color) !important;
            border: 2px solid var(--primary-color) !important;
            border-radius: 0.5rem;
            transition: background 0.2s, color 0.2s;
        }

        .register-btn:hover {
            background: var(--primary-color) !important;
            color: white !important;
        }

        .brand-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .brand-logo img {
            height: 72px;
            width: auto;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.08));
        }
        .login-title {
            text-align: center;
            font-size: 2.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            text-align: center;
            font-size: 1.125rem;
            color: #64748b;
            margin-bottom: 2rem;
        }
        @media (max-width: 500px) {
            .form-container {
                padding: 1.5rem 0.5rem;
            }
            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-outer-container">
        <div class="login-card">
            
            <div class="login-card-right">
                <div class="login-title">User Registration</div>
                <div class="login-subtitle">Join our elite team and help manage the ChicAura supply chain ecosystem</div>                
                <form class="space-y-6" action="{{ route('register.admin.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role" value="admin">

                    <div>
                        <label for="name" class="form-label block text-sm font-medium">Full Name</label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter Name">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="form-label block text-sm font-medium">Email address</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter Email">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="form-label block text-sm font-medium">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter password">
                        </div>
                    </div>

                    <div>
                        <label for="admin_code" class="form-label block text-sm font-medium">Role</label>
                        <div class="mt-1">
                            <select id="role" name="role" class="form-input block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="admin">Administrator</option>
                                <option value="supplier">Supplier</option> 
                                <option value="wholesaler">Wholesaler</option>
                            </select>
                        </div>
                                
                    </div>
                    
                  <!-- Dynamic Fields -->
                    <div id="dynamicFields" class="dynamic-section space-y-6"></div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Register User
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            font-family: 'Figtree', 'Open Sans', 'Helvetica Neue', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-outer-container {
            width: 600px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
        }
        .login-card {
            display: flex;
            flex-direction: row;
            background: #fff;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            border-radius: 1.5rem;
            overflow: hidden;
            max-width: 800px;
            width: 100%;
            min-height: 420px;
        }
        .login-card-left {
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 240px;
            width: 35%;
            padding: 2rem 1.5rem;
        }
        .login-logo-img {
            max-width: 140px;
            width: 100%;
            height: auto;
            display: block;
        }
        .login-card-right {
            flex: 1;
            padding: 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            font-size: 1.125rem;
            color: #64748b;
            margin-bottom: 2rem;
        }
        @media (max-width: 900px) {
            .login-card {
                max-width: 98vw;
            }
        }
        @media (max-width: 700px) {
            .login-card {
                flex-direction: column;
                min-height: unset;
            }
            .login-card-left, .login-card-right {
                width: 100%;
                min-width: unset;
                padding: 2rem 1rem;
            }
            .login-card-left {
                justify-content: center;
                border-bottom: 1px solid #e5e7eb;
            }
        }
        @media (max-width: 500px) {
            .login-card {
                border-radius: 0.5rem;
            }
            .login-title {
                font-size: 1.5rem;
            }
            .login-card-left, .login-card-right {
                padding: 1.25rem 0.5rem;
            }
        }
    </style>
</body>
</html>
<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    let passwordVisible = false;
    if (togglePassword && passwordInput && eyeIcon) {
        togglePassword.addEventListener('click', function () {
            passwordVisible = !passwordVisible;
            passwordInput.type = passwordVisible ? 'text' : 'password';
            eyeIcon.innerHTML = passwordVisible
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.362-2.568A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.293 5.032M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        });
    }
</script>
 <script>
    const roleSelect = document.getElementById('role');
    const dynamicFields = document.getElementById('dynamicFields');

    const fieldTemplates = {
      administrator: '',
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
                <input id="phone" name="phone" type="tel" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter Phone Number">
            </div>
        </div>

        <div>
            <label for="license_document" class="form-label block text-sm font-medium">Application Form</label>
            <div class="mt-1">
                <input id="license_document" name="license_document" type="file" required class="form-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
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
        `,
      manufacturer: `
        <div> 
            <label for="business_address" class="form-label block text-sm font-semibold mb-2">Factory Address</label>
            <textarea id="business_address" name="business_address" rows="3" required class="form-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter your complete factory address"></textarea>
        </div>

        <div>
            <label for="phone" class="form-label block text-sm font-semibold mb-2">Business Phone</label>
            <input id="phone" name="phone" type="tel" required class="form-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter your phone number">
        </div>

                    
        <div>
            <label for="license_document" class="form-label block text-sm font-semibold mb-2">Manufacturing License</label>
            <input id="license_document" name="license_document" type="file" required class="form-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
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
                <input id="phone" name="phone" type="tel" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter Phone Number">
            </div>
        </div>

        <div>
            <label for="license_document" class="form-label block text-sm font-medium">Application Form</label>
            <div class="mt-1">
                <input id="license_document" name="license_document" type="file" required class="form-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
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
