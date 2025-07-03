<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center ">
        <div class="w-full max-w-md bg-gray-100  rounded-lg p-8">
            <h2 class="text-2xl font-bold text-center text-indigo-700 mb-6">Sign in to your account</h2>
    <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
                <div>
            <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:text-indigo-800 font-medium" href="{{ route('password.request') }}">
                        Forgot your password?
            </a>
            @endif
                </div>

                <div>
                    <button type="submit" class="w-full py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow transition">Log in</button>
                </div>
            </form>
            <div class="mt-6 text-center">
                <span class="text-gray-600">Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-pink-500 hover:underline font-semibold ml-1">Sign up</a>
            </div>
        </div>
    </div>
</x-guest-layout>