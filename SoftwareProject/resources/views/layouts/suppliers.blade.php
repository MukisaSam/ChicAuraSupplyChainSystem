<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Reports Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('internal_css')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }

        .dark {
            background-color: #1f2937;
            color: #f3f4f6;
        }

        .dark .header {
            background-color: #374151;
        }

        .dark .sidebar {
            background-color: #1f2937;
        }

        .dark .sidebar a {
            color: #d1d5db;
        }

        .dark .sidebar a:hover {
            background-color: #374151;
        }

        .sidebar {
            transition: width 0.3s ease;
            z-index: 40;
            background-color: #ffffff;
            top: 60px; /* Added space below header */
        }

        .sidebar.collapsed {
            width: 64px;
        }

        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        .header {
            z-index: 50;
            background-color: #ffffff;
            transition: background-color 0.3s;
            height: 64px; /* Fixed height for header */
        }

        .content {
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed ~ .content {
            margin-left: 64px;
        }
        
    </style>
</head>

<body class="bg-gray-100">
    <!-- Main Container -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
         <div class="sidebar fixed left-0 w-64 h-[calc(100vh-60px)] p-4 shadow-lg flex flex-col justify-between">
            <div>
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('supplier.dashboard') }}" class=" @yield('dashboard','flex items-center p-2 text-gray-600 hover:bg-gray-100 rounded-lg')">
                                <i class="fas fa-home mr-3"></i>
                                <span class="sidebar-text">Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.orders') }}" 
                               class="@yield('admin_orders','flex items-center p-2 text-gray-600 hover:bg-gray-100 rounded-lg')">
                                <i class="fas fa-right-left mr-3"></i>
                                <span class="sidebar-text">Supply Requests</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('supplier.analytics') }}"
                                class="@yield('supplier_analytics','flex items-center p-2 text-gray-600 hover:bg-gray-100 rounded-lg')">
                                <i class="fas fa-chart-line mr-3"></i>
                                <span class="sidebar-text">Analytics</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reports') }}" 
                            class="@yield('admin_reports','flex items-center p-2 text-gray-600 hover:bg-gray-100 rounded-lg')">
                                <i class="fas fa-folder mr-3"></i>
                                <span class="sidebar-text">Reports</span>
                            </a>
                        </li>                        
                    </ul>
                </nav>
            </div>
            <div class="flex items-center p-2">
                <i class="fas fa-moon mr-3"></i>
                <span class="sidebar-text">Dark Mode</span>
                <label class="ml-auto inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" id="darkModeToggle">
                    <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-gray-600">
                        <div
                            class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5">
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Main Content with padding to avoid overlap with fixed header -->
        <!-- Header -->
        <div class="header fixed top-0 left-0 right-0 shadow-md px-6 py-4 flex justify-between items-center" style="background-color: rgb(250, 131, 131);">
            <div class="flex items-center">
                <img src="{{asset('assets/CA-WORD2.png')}}" alt="" srcset="" class="h-10">
            </div>
            <div class="relative flex items-center space-x-4">
                <i class="fas fa-search text-gray-600 cursor-pointer"></i>
                <i class="fas fa-bell text-gray-600 cursor-pointer"></i>
                <i class="far fa-comment-dots text-gray-600 cursor-pointer"></i>
                <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                <button id="dropdownToggle">
                    <i class="fa-solid fa-angle-down cursor-pointer"></i>
                </button>
                <div id="dropdownMenu" class="absolute right-0 top-12 w-48 bg-white rounded-lg shadow-lg hidden z-50 border border-gray-200 overflow-hidden">
                    <ul class="text-sm text-gray-700">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="content flex-1 px-6 ml-64 mt-20">
            @yield('content')
            
        </div>
    </div>

    <!-- JavaScript for Charts -->
    <script>
        @yield('js')
        // Dropdown toggle
        const dropdownToggle = document.getElementById('dropdownToggle');
        const dropdownMenu = document.getElementById('dropdownMenu');

        dropdownToggle.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
        });

        // Optional: Click outside to close
        document.addEventListener('click', (e) => {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

    </script>
    
</body>

</html>


        
    