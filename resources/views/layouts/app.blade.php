<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Booking Bus System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js for dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex bg-gray-100">
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col">
            <!-- Sticky Header -->
            <header class="bg-white shadow sticky top-0 z-30">
                <div class="max-w-7xl mx-auto py-4 px-6 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 9V7a5 5 0 1110 0v2M5 17h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xl font-extrabold tracking-tight text-indigo-900">Booking Bus System</span>
                    </div>
                    @include('layouts.navigation')
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 bg-white shadow-inner  p-8 ">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>