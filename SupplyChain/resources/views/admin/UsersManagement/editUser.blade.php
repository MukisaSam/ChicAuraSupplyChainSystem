@extends('layouts.admin-dashboard')

@section('content')
            <main class=" flex-1 overflow-y-auto p-6">
                @if($record)
                <form action="{{ route('admin.users.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Primary Key -->
                <input type="text" name="id" class="hidden"  value="{{$record->id}}">  

                <!-- Page Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-black">Add User</h1>
                        <p class="text-gray-500 mt-1">Enter the new user's personal and account information to create their profile.</p>
                    </div>
                    <div class="flex space-x-2 mt-4 md:mt-0">
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                            <i class="fa-solid fa-xmark mr-2"></i> Delete Account
                        </button>
                    </div>
                </div>
                
                <!-- Profile Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 mb-6 shadow-xl">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="relative mb-4 md:mb-0 md:mr-6">
                            @if($record->role == 'admin')
                                <div class="w-24 h-24 md:w-32 md:h-32 bg-purple-100 rounded-full border-4 border-white shadow-lg overflow-hidden flex items-center justify-center">
                                    <i class="fa-solid fa-user-tie text-blue-600 text-[70px]"></i>
                                </div>
                            @endif
                            @if($record->role == 'supplier')
                                <div class="w-24 h-24 md:w-32 md:h-32 bg-green-100 rounded-full border-4 border-white shadow-lg overflow-hidden flex items-center justify-center">
                                    <i class="fas fa-truck-loading text-green-600 text-[70px]"></i>
                                </div>
                            @endif
                            @if($record->role == 'manufacturer')
                                <div class="w-24 h-24 md:w-32 md:h-32 bg-yellow-100 rounded-full border-4 border-white shadow-lg overflow-hidden flex items-center justify-center">
                                    <i class="fas fa-industry text-yellow-600 text-[70px]"></i>
                                </div>
                            @endif
                            @if($record->role == 'wholesaler')
                                <div class="w-24 h-24 md:w-32 md:h-32 bg-purple-100 rounded-full border-4 border-white shadow-lg overflow-hidden flex items-center justify-center">
                                    <i class="fas fa-boxes text-purple-600 text-[70px]"></i>
                                </div>
                            @endif
                        </div>
                        <div class="text-center md:text-left">
                            <h1 class="text-2xl md:text-3xl font-bold text-white">{{$record->name}}</h1>
                            <div class="flex flex-wrap items-center justify-center md:justify-start mt-2 gap-2">
                                <span class="bg-blue-200 text-blue-800 px-3 py-1 rounded-full text-xs md:text-sm font-medium">{{$record->role}}</span>
                                <span class="bg-[rgba(217,119,6,0.2)] text-[#fcd34d] px-3 py-1 rounded-full text-xs md:text-sm font-medium">Pending</span>
                            </div>
                            <p class="text-blue-100 mt-2">{{$record->email}}</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Users Table -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="card-gradient rounded-2xl p-4">
                            <h2 class="text-xl font-bold mb-6 pb-3 border-b border-gray-200">Personal Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Company / Firm Name</label>
                                    <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{$record->name}}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Business Email</label>
                                    <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{$record->email}}">
                                </div>
                                @if($record->role != 'admin') 
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                                    <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{$record->phone}}">
                                </div>
                                @endif
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Role</label>
                                    <input type="text" name="role" class="w-full px-4 py-3 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{$record->role}}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">New Password</label>
                                    <div class="relative">
                                        <input type="password" id="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" placeholder="Change Old Password">
                                        <div class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 cursor-pointer">
                                            <i class="fas fa-eye"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                                    <div class="relative">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" placeholder="Confirm new password">
                                        <div class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 cursor-pointer">
                                            <i class="fas fa-eye"></i>
                                        </div>
                                    </div>
                                </div>
                                @if($record->role != 'admin') 
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Business Address</label>
                                    <input type="text" name="business_address" class="w-full px-4 py-3 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{$record->business_address}}">
                                </div>
                                @endif
                                @if($record->role == 'supplier')
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Materials Supplied</label>
                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-4 py-3 border border-gray-300  rounded-lg hover:ring-2 hover:ring-blue-500 hover:border-transparent bg-white">
                                        <?php
                                          $allOptions = ['fabric','thread','buttons','zippers','dyes','other'];
                                        ?>
                                        @foreach($allOptions as $option)
                                            <div><input type="checkbox" name="materials_supplied[]" value="{{$option}}" @if(in_array($option, $materials_supplied)) checked @endif><label class="ml-1" for="">{{ ucfirst($option) }}</label></div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @if($record->role == 'manufacturer')
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Production Capacity</label>
                                    <input type="text" name="production_capacity" class="w-full px-4 py-3 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{ $record->production_capacity }}">
                                </div> 
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Specialization</label>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 py-3 border border-gray-300  rounded-lg hover:ring-2 hover:ring-blue-500 hover:border-transparent bg-white">
                                        <?php
                                          $allOptions2 = ['casual_wear','formal_wear','sports_wear','evening_wear','accessories','other'];
                                        ?>
                                        @foreach($allOptions2 as $option)
                                            <div><input type="checkbox" name="specialization[]" value="{{$option}}" @if(in_array($option, $specialization)) checked @endif><label class="ml-1" for="">{{ ucfirst(str_replace('_', ' ', $option)) }}</label></div>
                                        @endforeach
                                    </div>
                                </div> 
                                @endif
                                @if($record->role == 'wholesaler')
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Business Type</label>
                                    <input type="text" name="business_type" class="w-full px-4 py-3 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{ ucfirst(str_replace('_', ' ', $record->business_type)) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Preferred Categories</label>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 py-3 border border-gray-300 rounded-lg hover:ring-2 hover:ring-blue-500 hover:border-transparent bg-white">
                                        <?php
                                          $allOptions3 = ['casual_wear','formal_wear','sports_wear','evening_wear','accessories','other'];
                                        ?>
                                        @foreach($allOptions3 as $option)
                                            <div><input type="checkbox" name="preferred_categories[]" value="{{$option}}" @if(in_array($option, $preferred_categories)) checked @endif><label class="ml-1" for="">{{ ucfirst(str_replace('_', ' ', $option)) }}</label></div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Monthy Order Volume</label>
                                    <input type="text" name="monthly_order_volume" class="w-full px-4 py-3 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{ $record->monthly_order_volume }}">
                                </div>                                
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-1 space-y-6">
                        <div class="card-gradient rounded-2xl p-4">
                            <h2 class="text-xl font-bold mb-6 pb-3 border-b">Profile Photo</h2>
                            <div class="flex flex-col items-center">                                
                                <div class="relative inline-block cursor-pointer mb-4">
                                    <div class="rounded-full overflow-hidden w-[120px] h-[120px] border-none bg-blue-100 flex items-center justify-center">
                                        <i class="fa-solid fa-user-tie text-blue-600 text-[80px]"></i>
                                    </div>
                                    <div class="absolute bottom-2.5 right-2.5 bg-white rounded-full w-9 h-9 flex items-center justify-center shadow-lg">
                                        <i class="fas fa-camera text-blue-600"></i>
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm mb-4 text-center">
                                    Recommended size: 300x300 pixels. JPG, GIF or PNG.
                                </p>
                                <label class="btn-secondary w-full cursor-pointer inline-flex items-center justify-center p-2 rounded">
                                     <i class="fas fa-upload mr-2"></i> Upload New Photo
                                    <input type="file" class="hidden" name="profile_picture" accept=".jpg,.jpeg,.gif,.png" id="photoInput" />
                                </label>
                                <p id="fileName" class="text-gray-600 text-sm mt-2 text-center"></p>
                            </div>
                        </div>
                        <div class="card-gradient rounded-2xl p-4">
                            <h2 class="text-xl font-bold mb-6 pb-3 border-b">Documentation</h2>
                            <div class="flex flex-col items-center">                                
                                <div class="relative inline-block cursor-pointer mb-4">
                                    <div class="rounded-full overflow-hidden w-[120px] h-[120px] border-none flex items-center justify-center">
                                        <i class="fa-regular fa-file-pdf text-[80px]" style="color: #b00b00;"></i>
                                    </div>
                                </div>
                                @if($record->profile_picture)
                                <p class="text-gray-600 text-sm mb-4 text-center">
                                    {{$record->license_document}}
                                </p>
                                <a href="{{ asset('storage/'.$record->document_path) }}" target="_blank" class="btn-secondary w-full text-center">
                                    View Document
                                </a>
                                @else
                                    <p class="text-gray-600 text-sm mb-4 text-center">
                                        No document uploaded.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                </form>
                @else
                <div class="max-w-4xl mx-auto py-12">
                    <div class="error-illustration flex flex-col items-center justify-center text-center p-8 md:p-12 rounded-3xl bg-gradient-to-br from-white/5 to-gray-900/10 backdrop-blur-sm border border-white/10 shadow-xl">
                        <div class="relative mb-10">
                            <div class="absolute -top-8 -left-8 w-24 h-24 bg-blue-500 rounded-full filter blur-2xl opacity-20 animate-pulse"></div>
                            <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-red-500 rounded-full filter blur-2xl opacity-20 animate-pulse"></div>
                            
                            <div class="relative bg-gray-800 rounded-2xl p-6 md:p-10 border border-gray-700 shadow-2xl transform rotate-6">
                                <div class="bg-gray-900 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6 border-4 border-gray-700 shadow-inner">
                                    <i class="fas fa-search text-5xl text-red-500"></i>
                                </div>
                                <div class="space-y-2">
                                    <div class="h-4 bg-gray-700 rounded w-40 mx-auto"></div>
                                    <div class="h-4 bg-gray-700 rounded w-32 mx-auto"></div>
                                    <div class="h-4 bg-gray-700 rounded w-28 mx-auto"></div>
                                </div>
                            </div>
                        </div>

                        <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                            <span class="text-red-500">Info</span> Not Found
                        </h1>
                        
                        <p class="text-xl text-gray-300 max-w-2xl mb-10">
                            The information you're looking for doesn't exist or may have been moved. 
                            
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 mb-14">
                            <button class="px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center">
                                <i class="fa-solid fa-user mr-3"></i> Return to User Management
                            </button>
                            
                        </div>
                    </div>
                </div>
                @endif   
            </main>
   
    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Theme toggle
        document.querySelector('[data-theme-toggle]').addEventListener('click', function () {
            document.documentElement.classList.toggle('dark');
            const icon = this.querySelector('i');
            if (document.documentElement.classList.contains('dark')) {
                icon.classList.replace('fa-moon', 'fa-sun');
            } else {
                icon.classList.replace('fa-sun', 'fa-moon');
            }
        });

        // Password visibility toggle
        document.querySelectorAll('.relative input[type="password"]').forEach(input => {
            const toggleBtn = input.nextElementSibling;
            toggleBtn.addEventListener('click', () => {
                if (input.type === 'password') {
                    input.type = 'text';
                    toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        });

        //upload name
        const input = document.getElementById('photoInput');
        const fileNameDisplay = document.getElementById('fileName');

        input.addEventListener('change', function() {
          if (this.files && this.files.length > 0) {
            fileNameDisplay.textContent = `Selected file: ${this.files[0].name}`;
          } else {
            fileNameDisplay.textContent = '';
          }
        });
    </script>
@endsection