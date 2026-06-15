<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-xl font-semibold text-accent leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="glass-panel overflow-hidden rounded-2xl">
                <div class="p-6">
                    <p class="text-lg text-body">You're logged in!</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
