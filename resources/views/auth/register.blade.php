<x-guest-layout>
    <div class="glass-panel overflow-hidden rounded-2xl">
        <div class="p-6 space-y-1 text-center border-b border-sec/20">
            <h1 class="font-display text-2xl font-semibold text-accent">Create Account</h1>
            <p class="font-body text-sm text-body/60">Join the wedding celebration</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="p-6 space-y-4">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- First Name -->
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" autocomplete="given-name" />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>
                <!-- Last Name -->
                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" autocomplete="family-name" />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>
            </div>

            <!-- Guest Name -->
            <div>
                <x-input-label for="guest_name" :value="__('Display Name')" />
                <x-text-input id="guest_name" class="block mt-1 w-full" type="text" name="guest_name" :value="old('guest_name')" required autocomplete="name" />
                <x-input-error :messages="$errors->get('guest_name')" class="mt-2" />
                <p class="mt-1 text-xs text-body/50">How your name appears to other guests</p>
            </div>

            <!-- Username -->
            <div>
                <x-input-label for="username" :value="__('Username (optional)')" />
                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <hr class="border-sec/20">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Connection -->
                <div>
                    <x-input-label for="connection" :value="__('Connection')" />
                    <select id="connection" name="connection" class="block mt-1 w-full rounded-xl border border-sec/30 bg-white/70 px-4 py-3 text-sm text-night focus:border-sec focus:outline-none focus:ring-1 focus:ring-sec/50 backdrop-blur-sm" required>
                        <option value="">Select Connection</option>
                        @foreach($connections as $conn)
                            <option value="{{ $conn->value }}" {{ old('connection') === $conn->value ? 'selected' : '' }}>{{ $conn->label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('connection')" class="mt-2" />
                </div>
                <!-- Core Group -->
                <div>
                    <x-input-label for="core_group" :value="__('Core Group')" />
                    <select id="core_group" name="core_group" class="block mt-1 w-full rounded-xl border border-sec/30 bg-white/70 px-4 py-3 text-sm text-night focus:border-sec focus:outline-none focus:ring-1 focus:ring-sec/50 backdrop-blur-sm" required>
                        <option value="">Select Group</option>
                        @foreach($coreGroups as $cg)
                            <option value="{{ $cg->value }}" {{ old('core_group') === $cg->value ? 'selected' : '' }}>{{ $cg->label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('core_group')" class="mt-2" />
                </div>
            </div>

            <!-- Specific Relationship -->
            <div>
                <x-input-label for="specific_relationship" :value="__('Your Relationship (optional)')" />
                <x-text-input id="specific_relationship" class="block mt-1 w-full" type="text" name="specific_relationship" :value="old('specific_relationship')" placeholder="e.g., Best Man, Bridesmaid, Cousin" />
                <x-input-error :messages="$errors->get('specific_relationship')" class="mt-2" />
            </div>

            <hr class="border-sec/20">

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
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
            </div>

            <hr class="border-sec/20">

            <!-- Address -->
            <div>
                <x-input-label for="address" :value="__('Street Address (optional)')" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" autocomplete="street-address" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <x-input-label for="city" :value="__('City')" />
                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" autocomplete="address-level2" />
                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="state" :value="__('State')" />
                    <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state')" autocomplete="address-level1" />
                    <x-input-error :messages="$errors->get('state')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="zip" :value="__('Zip')" />
                    <x-text-input id="zip" class="block mt-1 w-full" type="text" name="zip" :value="old('zip')" autocomplete="postal-code" />
                    <x-input-error :messages="$errors->get('zip')" class="mt-2" />
                </div>
            </div>

            <hr class="border-sec/20">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="phone_email" :value="__('Phone Book Email (optional)')" />
                    <x-text-input id="phone_email" class="block mt-1 w-full" type="email" name="phone_email" :value="old('phone_email')" autocomplete="email" />
                    <x-input-error :messages="$errors->get('phone_email')" class="mt-2" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" autocomplete="tel" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="mobile" :value="__('Mobile')" />
                        <x-text-input id="mobile" class="block mt-1 w-full" type="tel" name="mobile" :value="old('mobile')" autocomplete="tel" />
                        <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end pt-2">
                <a class="underline text-sm text-body/60 hover:text-sec" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
                <x-primary-button class="ms-4">
                    {{ __('Create Account') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>