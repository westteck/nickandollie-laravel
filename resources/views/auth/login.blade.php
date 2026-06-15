<x-guest-layout>
    <div class="glass-panel overflow-hidden rounded-2xl">
        <div class="p-6 space-y-1 text-center">
            <h1 class="font-display text-2xl font-semibold text-accent">Welcome Back</h1>
            <p class="font-body text-sm text-body/60">Sign in to your account</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mx-6 mt-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4 p-6 pt-2">
            @csrf

            <!-- Email -->
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
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-sec/30 text-sec focus:ring-sec" name="remember">
                    <span class="ms-2 text-sm text-body/80">{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-body/60 hover:text-sec" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-primary-button class="w-full justify-center mt-2">
                {{ __('Sign In') }}
            </x-primary-button>
        </form>

        <div class="px-6 pb-6 text-center">
            <p class="text-sm text-body/60">
                Don't have an account?
                <a href="{{ route('register') }}" class="underline text-accent hover:text-sec">Create one</a>
            </p>
        </div>
    </div>
</x-guest-layout>