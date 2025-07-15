@if($htmlCode)
    <script>
      window.APP_ROUTES = {
        submitUrl: @json(route('register.newUser')),
      };
    </script>
    {!! $htmlCode !!}
@else
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Server Error</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Scripts -->
    <style>
        body {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'FigTree', 'Open Sans', 'Helvetica Neue', sans-serif;
        }
    </style>
</head>

<body class="bg-cover bg-center bg-fixed"
    style="background-image: linear-gradient(135deg,rgba(0, 0, 0, 0.2),rgba(0, 0, 0, 0.4) 100%), url('{{ asset('images/showroom.png') }}');">
    <div class="min-h-screen flex justify-center items-center text-white overflow-hidden p-5 ">
        <div class="text-center max-w-2xl w-full p-10 rounded-2xl shadow-xl relative z-10 animate-fadeIn"
            style="background: rgba(248, 250, 252, 0.95); backdrop-filter: blur(10px); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);">
            <div class="server-icon text-[120px] inline-block text-[#ff6b6b]">
                <i class="fas fa-server"></i>
            </div>
            <h1 class="text-4xl md:text-[42px] font-bold mb-4 animate-slideIn gradient-text text-black">
                Connection Failed !
            </h1>
            <p class="text-lg md:text-xl leading-relaxed mb-8 text-indigo-500 animate-fadeInUp-delay-200">
                We couldn't establish a connection to the server. This might be due to a temporary issue with your
                network connection, the website's server, or an incorrect URL.
            </p>

            <div class="flex justify-center flex-wrap gap-4 mt-5 animate-fadeInUp-delay-400">
                <a href="{{route('register_online')}}"
                    class="flex items-center gap-2.5 px-7 py-3 rounded-full font-semibold transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg bg-indigo-600"
                    style="border: 2px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(5px);">
                    <i class="fas fa-sync-alt text-lg"></i> Try Again
                </a>
                <a href="{{route('welcome')}}"
                    class="flex items-center gap-2.5 px-7 py-3 rounded-full font-semibold transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg bg-indigo-600"
                    style="border: 2px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(5px);">
                    <i class="fas fa-home text-lg"></i> Home Page
                </a>
            </div>

            <div class="mt-8 text-sm text-black animate-fadeIn-delay-600">
                Error: {{$serverMessage}}
            </div>
        </div>
    </div>
</body>

</html>    
@endif