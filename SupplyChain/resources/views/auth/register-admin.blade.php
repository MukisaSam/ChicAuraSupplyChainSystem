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
            border-color: #615fff !important;
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
            border-color: #615fff !important;
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
                <input id="phone" name="phone" type="tel" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>

        <div>
            <label for="license_document" class="form-label block text-sm font-medium">Business License</label>
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
            <input id="phone" name="phone" type="tel" required class="form-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter your business phone number">
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
                <input id="phone" name="phone" type="tel" required class="form-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>

        <div>
            <label for="license_document" class="form-label block text-sm font-medium">Business License</label>
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
</body>
</html>
