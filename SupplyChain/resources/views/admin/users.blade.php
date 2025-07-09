@extends('.layouts.app')

@section('inline_css')
<style>
.modal {
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
            z-index: 50; /* Below sidebar and modal content */
        }
        .modal.hidden {
            opacity: 0;
            visibility: hidden;
        }
        .modal:not(.hidden) {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            z-index: 70; /* Above sidebar and backdrop */
        }
        body.modal-open {
            overflow: hidden;
        }
</style>
@endsection

@section('admin_users')
flex items-center p-2 bg-gray-900 text-white rounded-lg
@endsection

@section('content')
<div class="mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">User Management</h3>
                <p class="text-gray-600">Manage users involved in the supply chain</p>
            </div>

            <!-- Add User Button -->
            <div class="mb-6">
                <button id="addUserButton" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    <i class="fas fa-plus mr-2"></i>Add New User
                </button>
            </div>

            <!-- User Distribution Chart -->
            <div class="bg-white p-4 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold mb-4">User Distribution by Department</h3>
                <p class="text-gray-600 mb-4">Number of users per department</p>
                <canvas id="userDistributionChart" class="w-full h-64"></canvas>
            </div>

            <!-- Users Table -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4">Users List</h3>
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-600">
                            <th class="p-2">Name</th>
                            <th class="p-2">Employee ID</th>
                            <th class="p-2">Role</th>
                            <th class="p-2">Contact</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t">
                            <td class="p-2">Mark</td>
                            <td class="p-2">00 22 669</td>
                            <td class="p-2">Adminstrator</td>
                            <td class="p-2">mark@adim</td>
                            <td class="p-2">
                                <button class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-edit"></i></button>
                                <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="p-2">Mohammad Ali</td>
                            <td class="p-2">22 35 205</td>
                            <td class="p-2">Supplier</td>
                            <td class="p-2">mohammad.ali@chicaura.com</td>
                            <td class="p-2">
                                <button class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-edit"></i></button>
                                <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="p-2">Ayes Siddika</td>
                            <td class="p-2">32 05 202</td>
                            <td class="p-2">Retailer</td>
                            <td class="p-2">ayes.siddika@chicaura.com</td>
                            <td class="p-2">
                                <button class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-edit"></i></button>
                                <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="p-2">Arif Khan</td>
                            <td class="p-2">112 56 589</td>
                            <td class="p-2">Manufacturer</td>
                            <td class="p-2">arif.khan@chicaura.com</td>
                            <td class="p-2">
                                <button class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-edit"></i></button>
                                <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Add User Modal -->
            <div id="addUserModal" class="fixed top-0 left-64 right-0 bottom-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden modal">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4 sm:mx-auto modal-content">
                    
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Add New User</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-600 mb-1">Name</label>
                            <input type="text" class="w-full p-2 border rounded-lg dark:border-gray-600" placeholder="Enter name">
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">Employee ID</label>
                            <input type="text" class="w-full p-2 border rounded-lg dark:border-gray-600 " placeholder="Enter employee ID">
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">Role</label>
                            <select class="w-full p-2 border rounded-lg dark:border-gray-600">
                                <option>Manufacturer</option>
                                <option>Administrator</option>
                                <option>Supplier</option>
                                <option>Whole Saler/Retailer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">Contact Email</label>
                            <input type="email" class="w-full p-2 border rounded-lg dark:border-gray-600" placeholder="Enter email">
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">Password</label>
                            <input type="password" class="w-full p-2 border rounded-lg dark:border-gray-600" placeholder="Password">
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">Confirm Password</label>
                            <input type="password" class="w-full p-2 border rounded-lg dark:border-gray-600" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="flex justify-end mt-6 space-x-2">
                        <button id="cancelAddUser" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200">Cancel</button>
                        <button id="saveUser" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">Save</button>
                    </div>
                </div>
            </div>
@endsection


@section('js')
 // User Distribution Chart
        const userDistributionCtx = document.getElementById('userDistributionChart').getContext('2d');
        new Chart(userDistributionCtx, {
            type: 'pie',
            data: {
                labels: ['Warehouse', 'Procurement', 'Logistics', 'Production', 'Finance'],
                datasets: [
                    {
                        label: 'Users',
                        data: [50, 30, 40, 35, 20],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true
            }
        });

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        darkModeToggle.addEventListener('change', () => {
            document.body.classList.toggle('dark', darkModeToggle.checked);
        });


        // Modal Toggle
        const addUserButton = document.getElementById('addUserButton');
        const addUserModal = document.getElementById('addUserModal');
        const cancelAddUser = document.getElementById('cancelAddUser');
        const saveUser = document.getElementById('saveUser');

        addUserButton.addEventListener('click', () => {
            addUserModal.classList.remove('hidden');
            document.body.classList.add('modal-open');
        });

        cancelAddUser.addEventListener('click', () => {
            addUserModal.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });

        saveUser.addEventListener('click', () => {
            // Placeholder for saving user data (requires backend integration)
            alert('User saved (backend integration needed)');
            addUserModal.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });

        // Close modal when clicking outside
        addUserModal.addEventListener('click', (e) => {
            if (e.target === addUserModal) {
                addUserModal.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }
        });
@endsection      
    