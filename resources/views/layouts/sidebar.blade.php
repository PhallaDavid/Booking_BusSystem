<div class="w-64 bg-indigo-900  text-white  p-4 flex flex-col">
    <div class="flex items-center mb-8">
        <svg class="w-8 h-8 text-emerald-400 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 9V7a5 5 0 1110 0v2M5 17h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z" />
        </svg>
        <h2 class="text-2xl font-extrabold tracking-tight">Booking Bus System</h2>
    </div>
    <nav class="flex-1 space-y-1">
        <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 flex items-center gap-2 {{ request()->routeIs('dashboard') ? 'bg-indigo-800 font-bold' : '' }}">
            <i class="fas fa-tachometer-alt text-emerald-300 w-5 h-5"></i>
            <span>Dashboard</span>
        </a>
        @can('offer-list')
        <a href="{{ route('offers.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 flex items-center gap-2 {{ request()->routeIs('offers.*') ? 'bg-indigo-800 font-bold' : '' }}">
            <i class="fas fa-gift text-pink-300 w-5 h-5"></i>
            <span>Offers</span>
        </a>
        @endcan
        <a href="{{ route('buses.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 flex items-center gap-2 {{ request()->routeIs('buses.*') ? 'bg-indigo-800 font-bold' : '' }}">
            <i class="fas fa-bus text-blue-300 w-5 h-5"></i>
            <span>Buses</span>
        </a>

        <a href="{{ route('bookings.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 flex items-center gap-2 {{ request()->routeIs('bookings.index') ? 'bg-indigo-800 font-bold' : '' }}">
            <i class="fas fa-receipt text-orange-300 w-5 h-5"></i>
            <span>Bookings</span>
        </a>
        <a href="{{ route('users.customers') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 flex items-center gap-2 {{ request()->routeIs('users.customers') ? 'bg-indigo-800 font-bold' : '' }}">
            <i class="fas fa-users text-green-300 w-5 h-5"></i>
            <span>Customers</span>
        </a>
        <a href="{{ route('settings.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 flex items-center gap-2 {{ request()->routeIs('settings.index') ? 'bg-indigo-800 font-bold' : '' }}">
            <i class="fas fa-cog text-yellow-300 w-5 h-5"></i>
            <span>Settings</span>
        </a>
    </nav>
    <div class="mt-auto text-xs z-50 text-indigo-200 pt-4 border-t border-indigo-800">&copy; {{ date('Y') }} Booking Bus System</div>
</div>