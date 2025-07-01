<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bus Booking System</title>
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

<body class=" min-h-screen flex flex-col items-center justify-center">
    <header class="w-full max-w-4xl flex justify-end ">
            @if (Route::has('login'))
        <nav class="flex gap-4">
                    @auth
            <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-emerald-500 text-white rounded-lg font-semibold shadow hover:bg-emerald-600 transition">Dashboard</a>
                    @else
            <a href="{{ route('login') }}" class="px-5 py-2 bg-indigo-500 text-white rounded-lg font-semibold shadow hover:bg-indigo-600 transition">Log in</a>
                        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="px-5 py-2 bg-pink-500 text-white rounded-lg font-semibold shadow hover:bg-pink-600 transition">Register</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
    <main class="flex flex-col items-center justify-center flex-1 w-full px-4">

    </main>
    <footer class="mt-10 text-gray-500 text-sm">&copy; {{ date('Y') }} Bus Booking System. All rights reserved.</footer>
    </body>

</html>