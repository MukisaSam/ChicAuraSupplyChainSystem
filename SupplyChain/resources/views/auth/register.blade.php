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
            color: #1e293b !important;
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
            color: #1e293b !important;
            backdrop-filter: blur(5px);
        }

        .form-input2::placeholder {
            color: #1e293b !important;
        }

        .form-input2:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            border-color: #615fff !important;
            color: #1e293b !important;
        }

        select.form-input option {
            background-color: rgba(0, 0, 0, 0.7);
            color: var(--text-light);
        }

        .form-label {
            color: #1e293b !important;
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

<body class="bg-cover bg-center bg-fixed"
    style="background-image: linear-gradient(135deg,rgba(0, 0, 0, 0.2),rgba(0, 0, 0, 0.4) 100%), url('{{ asset('images/showroom.png') }}');">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="form-container py-8 shadow-2xl sm:rounded-xl sm:px-10 sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="sm:mx-auto sm:w-full sm:max-w-md mb-5">
                <div class="flex justify-center mb-8">
                    <img src="{{ asset('images/CA-WORD.png') }}" alt="ChicAura Logo" class="w-[200px] drop-shadow-lg">
                </div>
                <h2 class="mt-6 text-center text-5xl font-bold text-black drop-shadow-lg">
                    User Registration
                </h2>
            </div>
            <div class="space-y-6">

                <div>
                    <p class="text-center text-2xl font-bold mb-1">Online Registration</p>
                    <p class="text-center text-sm text-bold mb-5">Fill out the registration details through our online form and submit your information for approval and confirmation.</p>

                    <a href="{{route('register_online')}}"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Online Registration
                    </a>
                </div>

                <div class="flex items-center my-4">
                    <div class="flex-grow border-t border-black"></div>
                    <span class="mx-2 text-sm text-black">Or</span>
                    <div class="flex-grow border-t border-black"></div>
                </div>
                <div>
                    <p class="text-center text-2xl font-bold mb-1">Form Registration</p>
                    <p class="text-center text-sm text-bold mb-5">Download a form, provide the necessary information and submit it for review and processing.</p>
                    <div class="grid lg:grid-cols-2 gap-4">
                        <a href="{{asset('templates/SUPPLIER APPLICATION FORM.docx') }}"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-center text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Download<br>Supplier Form
                        </a>
                        <a href="{{asset('templates/WHOLESALER APPLICATION FORM.docx') }}"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-center text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Download<br>Wholesaler Form
                        </a>
                    </div>
                </div>
                <form action="{{ route('register.newUser') }}" method="POST" enctype="multipart/form-data"
                    class="grid grid-cols-1 lg:grid-cols-5 gap-1">
                    @csrf
                    <div class="flex flex-wrap content-center lg:col-span-4">
                        <input id="license_document" name="license_document" type="file" required
                            class="form-input flex justify-center w-full px-4 py-4 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <button type="submit"
                        class="w-full lg:col-span-1 flex justify-center px-4 py-3 mt-1 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 outline-none transition duration-200 transform hover:scale-105 cursor-pointer">

                        <svg viewBox="0 0 24 24" class="h-auto w-3xl" fill="none" xmlns="http://www.w3.org/2000/svg"
                            stroke="#ffffff">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M20.7639 12H10.0556M3 8.00003H5.5M4 12H5.5M4.5 16H5.5M9.96153 12.4896L9.07002 15.4486C8.73252 16.5688 8.56376 17.1289 8.70734 17.4633C8.83199 17.7537 9.08656 17.9681 9.39391 18.0415C9.74792 18.1261 10.2711 17.8645 11.3175 17.3413L19.1378 13.4311C20.059 12.9705 20.5197 12.7402 20.6675 12.4285C20.7961 12.1573 20.7961 11.8427 20.6675 11.5715C20.5197 11.2598 20.059 11.0295 19.1378 10.5689L11.3068 6.65342C10.2633 6.13168 9.74156 5.87081 9.38789 5.95502C9.0808 6.02815 8.82627 6.24198 8.70128 6.53184C8.55731 6.86569 8.72427 7.42461 9.05819 8.54246L9.96261 11.5701C10.0137 11.7411 10.0392 11.8266 10.0493 11.9137C10.0583 11.991 10.0582 12.069 10.049 12.1463C10.0387 12.2334 10.013 12.3188 9.96153 12.4896Z"
                                    stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </g>
                        </svg>
                    </button>
                </form>


            </div>
        </div>
    </div>
    <script>

    </script>
</body>

</html>