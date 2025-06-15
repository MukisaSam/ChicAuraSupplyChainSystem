@extends('.layouts.admin')
@section('admin_dashboard')
 flex items-center p-2 bg-gray-900 text-white rounded-lg
@endsection
@section('content')
            <div class="mb-5">
                <h2 class="text-2xl font-semibold text-gray-800 ">Good Morning, {{ Auth::user()->name }}</h2>
                <p class="text-xl text-gray-600">Administrator Dashboard</p>
            </div>
        <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-users text-blue-500 mr-3"></i>
                        <div>
                            <p class="text-gray-600">Total Employee</p>
                            <h3 class="text-2xl font-bold">2000.00</h3>
                            <p class="text-green-500">+2.95%</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-arrow-up text-orange-500 mr-3"></i>
                        <div>
                            <p class="text-gray-600">Total Tasks</p>
                            <h3 class="text-2xl font-bold">109.00</h3>
                            <p class="text-green-500">+2.95%</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md col-span-2">
                    <div class="flex flex-col items-center justify-between">
                        <div>
                            <p class="text-gray-600">Employee Reports</p>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center">
                                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-1"></span>
                                    <span>Last Month: $487</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-3 h-3 bg-purple-500 rounded-full mr-1"></span>
                                    <span>This Month: $506</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-3 h-3 bg-orange-500 rounded-full mr-1"></span>
                                    <span>Achieved</span>
                                </div>
                            </div>
                        </div>
                        <canvas id="employeeReportsChart" class="w-full h-24"></canvas>
                    </div>
                </div>
            </div>

            <!-- Charts and Notifications -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Project Delivery Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Project Deliveries</h3>
                    <p class="text-gray-600 mb-4">7 Projects</p>
                    <canvas id="projectDeliveriesChart" class="w-full h-64"></canvas>
                </div>

                <!-- Notifications -->
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Notifications</h3>
                        <a href="#" class="text-blue-500 text-sm">VIEW ALL</a>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                            <div>
                                <p class="font-semibold">JAXSON STANTON</p>
                                <p class="text-gray-600 text-sm">04 Apr, 2021 04:00 PM</p>
                            </div>
                        </li>
                        <li class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                            <div>
                                <p class="font-semibold">JAMES CULLINANE</p>
                                <p class="text-gray-600 text-sm">04 Apr, 2021 04:00 PM</p>
                            </div>
                        </li>
                        <li class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                            <div>
                                <p class="font-semibold">WILSON BOTOSH</p>
                                <p class="text-gray-600 text-sm">03 Apr, 2021 02:00 PM</p>
                            </div>
                        </li>
                        <li class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                            <div>
                                <p class="font-semibold">AHMAD HERWITZ</p>
                                <p class="text-gray-600 text-sm">02 Apr, 2021 01:00 PM</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Top Products Table -->
            <div class="bg-white p-4 rounded-lg shadow-md mt-6">
                <h3 class="text-lg font-semibold mb-4">Top Products</h3>
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-600">
                            <th class="p-2">#</th>
                            <th class="p-2">Name</th>
                            <th class="p-2">Employee ID</th>
                            <th class="p-2">Employee In</th>
                            <th class="p-2">Employee Out</th>
                            <th class="p-2">Popularity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t">
                            <td class="p-2">01</td>
                            <td class="p-2">Halim Abdul</td>
                            <td class="p-2">00 22 669</td>
                            <td class="p-2">09:36 am</td>
                            <td class="p-2">07:22 pm</td>
                            <td class="p-2"><span class="bg-green-100 text-green-600 px-2 py-1 rounded-full">85%</span>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="p-2">02</td>
                            <td class="p-2">Mohammad Ali</td>
                            <td class="p-2">22 35 205</td>
                            <td class="p-2">10:30 am</td>
                            <td class="p-2">08:29 pm</td>
                            <td class="p-2"><span
                                    class="bg-purple-100 text-purple-600 px-2 py-1 rounded-full">20%</span></td>
                        </tr>
                        <tr class="border-t">
                            <td class="p-2">03</td>
                            <td class="p-2">Ayes Siddika</td>
                            <td class="p-2">32 05 202</td>
                            <td class="p-2">09:00 am</td>
                            <td class="p-2">06:59 pm</td>
                            <td class="p-2"><span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full">65%</span>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="p-2">04</td>
                            <td class="p-2">Arif Khan PK</td>
                            <td class="p-2">112 56 589</td>
                            <td class="p-2">09:05 am</td>
                            <td class="p-2">07:00 pm</td>
                            <td class="p-2"><span class="bg-pink-100 text-pink-600 px-2 py-1 rounded-full">88%</span>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="p-2">05</td>
                            <td class="p-2">Bahanur Khabir</td>
                            <td class="p-2">118 02 000</td>
                            <td class="p-2">09:32 am</td>
                            <td class="p-2">05:25 pm</td>
                            <td class="p-2"><span
                                    class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full">65%</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

@endsection
@section('js')
        // Project Deliveries Chart
        const projectDeliveriesCtx = document.getElementById('projectDeliveriesChart').getContext('2d');
        new Chart(projectDeliveriesCtx, {
            type: 'line',
            data: {
                labels: ['Oct 2021', 'Nov 2021', 'Dec 2021', 'Jan 2022', 'Feb 2022', 'Mar 2022'],
                datasets: [
                    {
                        label: 'Project Deliveries',
                        data: [6, 8, 5, 7, 4, 8],
                        fill: true,
                        backgroundColor: 'rgba(0, 255, 0, 0.1)',
                        borderColor: 'rgba(0, 255, 0, 0.5)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 12
                    }
                }
            }
        });

        // Employee Reports Chart
        const employeeReportsCtx = document.getElementById('employeeReportsChart').getContext('2d');
        new Chart(employeeReportsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Last Month',
                        data: [487, 450, 470, 460, 480, 487],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'This Month',
                        data: [506, 490, 500, 510, 495, 506],
                        borderColor: 'rgba(153, 102, 255, 1)',
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Achieved',
                        data: [400, 420, 410, 430, 415, 425],
                        borderColor: 'rgba(255, 159, 64, 1)',
                        fill: false,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 600
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        darkModeToggle.addEventListener('change', () => {
            document.body.classList.toggle('dark', darkModeToggle.checked);
            document.body.classList.toggle('bg-gray-100', !darkModeToggle.checked);
            document.body.classList.toggle('bg-gray-900', darkModeToggle.checked);
            document.body.classList.toggle('text-gray-800', !darkModeToggle.checked);
            document.body.classList.toggle('text-white', darkModeToggle.checked);
        });
        
@endsection


        
    