@extends('.layouts.admin')
@section('admin_inventory')
flex items-center p-2 bg-gray-900 text-white rounded-lg
@endsection
@section('content')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Summary Card -->
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-users text-blue-500 mr-3"></i>
                        <div>
                            <p class="text-gray-600">Total Teams</p>
                            <h3 class="text-2xl font-bold">12</h3>
                            <p class="text-green-500">+2 new teams this month</p>
                        </div>
                    </div>
                </div>

                <!-- Active Projects Card -->
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-folder text-orange-500 mr-3"></i>
                        <div>
                            <p class="text-gray-600">Active Projects</p>
                            <h3 class="text-2xl font-bold">7</h3>
                            <p class="text-green-500">+1 ongoing</p>
                        </div>
                    </div>
                </div>

                <!-- Team Performance Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Team Performance Over Time</h3>
                    <p class="text-gray-600 mb-4">Performance scores by team</p>
                    <canvas id="teamPerformanceChart" class="w-full h-64"></canvas>
                </div>

                <!-- Team List Table -->
                <div class="bg-white p-4 rounded-lg shadow-md col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Team List</h3>
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-gray-600">
                                <th class="p-2">Team Name</th>
                                <th class="p-2">Team Leader</th>
                                <th class="p-2">Members</th>
                                <th class="p-2">Progress</th>
                                <th class="p-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t">
                                <td class="p-2">Alpha Innovators</td>
                                <td class="p-2">Halim Abdul</td>
                                <td class="p-2">8</td>
                                <td class="p-2"><span
                                        class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full">75%</span></td>
                                <td class="p-2"><span
                                        class="bg-green-100 text-green-600 px-2 py-1 rounded-full">Active</span></td>
                            </tr>
                            <tr class="border-t">
                                <td class="p-2">Beta Pioneers</td>
                                <td class="p-2">Mohammad Ali</td>
                                <td class="p-2">6</td>
                                <td class="p-2"><span
                                        class="bg-green-100 text-green-600 px-2 py-1 rounded-full">90%</span></td>
                                <td class="p-2"><span
                                        class="bg-green-100 text-green-600 px-2 py-1 rounded-full">Active</span></td>
                            </tr>
                            <tr class="border-t">
                                <td class="p-2">Gamma Creators</td>
                                <td class="p-2">Ayes Siddika</td>
                                <td class="p-2">5</td>
                                <td class="p-2"><span
                                        class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full">60%</span></td>
                                <td class="p-2"><span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full">On
                                        Hold</span></td>
                            </tr>
                            <tr class="border-t">
                                <td class="p-2">Delta Dynamos</td>
                                <td class="p-2">Arif Khan</td>
                                <td class="p-2">7</td>
                                <td class="p-2"><span
                                        class="bg-pink-100 text-pink-600 px-2 py-1 rounded-full">85%</span></td>
                                <td class="p-2"><span
                                        class="bg-green-100 text-green-600 px-2 py-1 rounded-full">Active</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Top Performers -->
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4">Top Performers</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                            <div>
                                <p class="font-semibold">Halim Abdul</p>
                                <p class="text-gray-600 text-sm">Team Alpha - 95% Performance</p>
                            </div>
                        </li>
                        <li class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                            <div>
                                <p class="font-semibold">Ayes Siddika</p>
                                <p class="text-gray-600 text-sm">Team Gamma - 90% Performance</p>
                            </div>
                        </li>
                        <li class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                            <div>
                                <p class="font-semibold">Mohammad Ali</p>
                                <p class="text-gray-600 text-sm">Team Beta - 88% Performance</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
@endsection

@section('js')
// Team Performance Chart
        const teamPerformanceCtx = document.getElementById('teamPerformanceChart').getContext('2d');
        new Chart(teamPerformanceCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Alpha Innovators',
                        data: [85, 88, 90, 87, 89, 92],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Beta Pioneers',
                        data: [80, 82, 85, 83, 84, 87],
                        borderColor: 'rgba(255, 99, 132, 1)',
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Gamma Creators',
                        data: [75, 78, 80, 79, 82, 85],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Delta Dynamos',
                        data: [82, 85, 87, 86, 88, 90],
                        borderColor: 'rgba(153, 102, 255, 1)',
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
                        max: 100,
                        ticks: {
                            callback: function (value) {
                                return value + '%';
                            }
                        }
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
    