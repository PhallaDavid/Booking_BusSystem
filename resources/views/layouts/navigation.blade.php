<nav x-data="{ open: false }" class="bg-white border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end h-16">
            <!-- Settings Modal Trigger -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative">
                    <button class="inline-flex items-center" @click="open = !open">
                        <i class="fas fa-user-circle text-indigo-500 text-xl mr-2"></i>
                        <span class="mr-2">{{ Auth::user()->name }}</span>
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-34 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200" style="display: none;">
                        <a href="{{ route('profile.edit') }}" class="flex  gap-2 px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition rounded">
                            <i class="fas fa-user text-blue-400"></i>
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition rounded">
                                <i class="fas fa-sign-out-alt text-emerald-400"></i>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>