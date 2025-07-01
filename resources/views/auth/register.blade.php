<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br ">
        <div class="w-full max-w-md  rounded-lg  p-8">
            <h2 class="text-2xl font-bold text-center text-indigo-700 mb-6">Create your account</h2>
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
                <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
                <div>
            <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
                <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

                <div>
                    <button type="submit" class="w-full py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow transition">Register</button>
                </div>
            </form>
            <div class="mt-6 text-center">
                <span class="text-gray-600">Already have an account?</span>
                <a href="{{ route('login') }}" class="text-indigo-500 hover:underline font-semibold ml-1">Sign in</a>
            </div>
        </div>
    </div>
</x-guest-layout>