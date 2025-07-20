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
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            font-family: 'Figtree', 'Open Sans', 'Helvetica Neue', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;

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

        .login-outer-container {
            width: 100vw;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
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
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.08));
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

            .login-card-left,
            .login-card-right {
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

            .login-card-left,
            .login-card-right {
                padding: 1.25rem 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-outer-container">
        <div class="login-card">
            <div class="login-card-left"
                style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="login-logo-img">
                <div class="login-welcome"
                    style="margin-top: 1.25rem; color: #475569; font-size: 1rem; text-align: center; max-width: 180px;">
                    Empowering seamless collaboration across the fashion supply chain.
                </div>
            </div>
            <div class="login-card-right">

                <div class="sm:mx-auto sm:w-full sm:max-w-md mb-5">

                    <h2 class="mt-6 text-center text-5xl font-bold text-black drop-shadow-lg">
                        User Registration
                    </h2>
                </div>
                <div class="space-y-2">

                    <div>
                        <p class="text-center text-2xl font-bold mb-1">Online Form Registration</p>
                        <p class="text-center text-sm text-bold mb-5">Fill out the registration details through our
                            online form and submit your information for approval and confirmation.</p>

                        <a href="{{route('register_online')}}"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Online Registration
                        </a>
                    </div>

                    <div class="flex items-center my-4">
                        <div class="flex-grow border-t border-black"></div>
                        <span class="mx-2 text-sm text-black">Or</span>
                        <div class="flex-grow border-t border-black"></div>
                    </div>
                    <div>
                        <p class="text-center text-2xl font-bold mb-1">Offline Form Registration</p>
                        <p class="text-center text-sm text-bold mb-5">Download a form, provide the necessary
                            information and submit it for review and processing.</p>
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
                                class="form-input flex justify-center w-full h-[65px] px-4 py-4 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        <button type="submit"
                            class="w-full lg:col-span-1 flex justify-center px-4 py-3 mt-1 h-[65px] border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 outline-none transition duration-200 transform hover:scale-105 cursor-pointer">

                            <svg viewBox="0 0 24 24" class="h-auto w-3xl" fill="none" xmlns="http://www.w3.org/2000/svg"
                                stroke="#ffffff">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M20.7639 12H10.0556M3 8.00003H5.5M4 12H5.5M4.5 16H5.5M9.96153 12.4896L9.07002 15.4486C8.73252 16.5688 8.56376 17.1289 8.70734 17.4633C8.83199 17.7537 9.08656 17.9681 9.39391 18.0415C9.74792 18.1261 10.2711 17.8645 11.3175 17.3413L19.1378 13.4311C20.059 12.9705 20.5197 12.7402 20.6675 12.4285C20.7961 12.1573 20.7961 11.8427 20.6675 11.5715C20.5197 11.2598 20.059 11.0295 19.1378 10.5689L11.3068 6.65342C10.2633 6.13168 9.74156 5.87081 9.38789 5.95502C9.0808 6.02815 8.82627 6.24198 8.70128 6.53184C8.55731 6.86569 8.72427 7.42461 9.05819 8.54246L9.96261 11.5701C10.0137 11.7411 10.0392 11.8266 10.0493 11.9137C10.0583 11.991 10.0582 12.069 10.049 12.1463C10.0387 12.2334 10.013 12.3188 9.96153 12.4896Z"
                                        stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                </g>
                            </svg>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>

</html>