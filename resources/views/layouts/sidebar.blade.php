<div class="w-64 bg-indigo-900  text-white  p-4 flex flex-col">
    <div class="flex items-center mb-8">
        <i class="fas fa-bus w-8 h-8 text-emerald-400 mr-2" style="font-size:2rem;"></i>
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
        <a href="{{ route('notifications.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 flex items-center gap-2 {{ request()->routeIs('notifications.index') ? 'bg-indigo-800 font-bold' : '' }}">
            <i class="fas fa-bell text-red-300 w-5 h-5"></i>
            <span>Notifications</span>
        </a>
        @can('settings-view')
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="w-full flex items-center justify-between py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 gap-2 focus:outline-none">
                <span class="flex items-center gap-2">
                    <i class="fas fa-cog text-yellow-300 w-5 h-5"></i>
                    <span>Settings</span>
                </span>
                <svg :class="{'transform rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" class="absolute left-0  w-full    mt-2 z-50" style="display: none;" x-transition>
                <a href="{{ route('settings.index') }}" class="block px-4 text-white py-2 hover:bg-indigo-800 flex items-center gap-2">
                    <i class="fas fa-cog text-yellow-300 w-4 h-4"></i>
                    <span>User</span>
                </a>
                <a href="{{ route('users.customers') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 flex items-center gap-2 {{ request()->routeIs('users.customers') ? 'bg-indigo-800 font-bold' : '' }}">
                    <i class="fas fa-users text-green-300 w-5 h-5"></i>
                    <span>Customers</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="block px-4 text-white py-2 hover:bg-indigo-800 flex items-center gap-2">
                    <i class="fas fa-id-badge text-purple-300 w-4 h-4"></i>
                    <span>Profile</span>
                </a>

            </div>
        </div>
        @endcan
    </nav>
    <div class="mt-auto text-xs z-50 text-indigo-200 pt-4 border-t border-indigo-800">&copy; {{ date('Y') }} Booking Bus System</div>
</div>