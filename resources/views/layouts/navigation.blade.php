<header class="site-header">
    <nav class="nav-shell relative mx-auto flex w-full items-center justify-between" x-data="{ mobileOpen: false }">

        <!-- Logo / Brand (always visible) -->
        <a href="{{ Auth::check() ? route('gallery') : route('home') }}"
           class="nav-brand" aria-label="Nick & Ollie home">
            <div class="logo-text font-display">
                <span class="logo-title">{{ Auth::check() ? 'Nick &amp; Ollie' : 'Welcome' }}</span>
            </div>
        </a>

        <!-- Desktop links (only when logged in) -->
        @auth
        <div class="nav-links">
            <a class="nav-link" href="{{ route('gallery') }}" aria-current="{{ request()->routeIs('gallery') ? 'page' : 'false' }}">Gallery</a>
            <a class="nav-link" href="{{ route('upload') }}" aria-current="{{ request()->routeIs('upload') ? 'page' : 'false' }}">Upload</a>
            <a class="nav-link" href="{{ route('phonebook') }}" aria-current="{{ request()->routeIs('phonebook') ? 'page' : 'false' }}">Phonebook</a>
            @if(auth()->user()->is_admin)
                <a class="nav-link" href="{{ route('admin.dashboard') }}" aria-current="{{ request()->routeIs('admin.*') ? 'page' : 'false' }}">Admin</a>
            @endif
        </div>
        @endauth

        <!-- Right side -->
        <div class="flex items-center gap-3">

            <!-- Theme toggle (always) -->
            <button type="button" class="nav-theme-toggle"
                    x-on:click="$dispatch('toggle-theme')"
                    aria-label="Toggle theme">
                <svg x-show="$root.classList.contains('dark')" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                </svg>
                <svg x-show="!$root.classList.contains('dark')" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>

            @auth
                <!-- User dropdown -->
                <div class="hidden lg:flex items-center gap-3" x-data="{ open: false }">
                    <button @click="open = ! open" class="nav-link flex items-center gap-1">
                        {{ Auth::user()->name }}
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-52 w-44 rounded-xl glass-panel p-2 z-50 top-12">
                        <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-xs uppercase tracking-widest hover:bg-white/20">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-xs uppercase tracking-widest hover:bg-white/20">Log Out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-cta hidden lg:inline-flex">Login</a>
            @endauth

            <!-- Mobile menu toggle (only when logged in) -->
            @auth
            <button type="button" class="mobile-menu-toggle lg:hidden"
                    x-on:click="mobileOpen = ! mobileOpen"
                    :aria-expanded="mobileOpen"
                    aria-controls="mobile-navigation"
                    aria-label="Open menu">
                <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
                <svg x-show="mobileOpen" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
            @endauth
        </div>

        <!-- Mobile dropdown (only when logged in) -->
        @auth
        <div class="mobile-nav lg:hidden"
             :class="{ 'hidden': !mobileOpen }"
             id="mobile-navigation"
             x-show="mobileOpen"
             x-transition>
            <a href="{{ route('gallery') }}" aria-current="{{ request()->routeIs('gallery') ? 'page' : 'false' }}">Gallery</a>
            <a href="{{ route('upload') }}" aria-current="{{ request()->routeIs('upload') ? 'page' : 'false' }}">Upload</a>
            <a href="{{ route('phonebook') }}" aria-current="{{ request()->routeIs('phonebook') ? 'page' : 'false' }}">Phonebook</a>
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" aria-current="{{ request()->routeIs('admin.*') ? 'page' : 'false' }}">Admin</a>
            @endif
            <a href="{{ route('profile.edit') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left uppercase tracking-widest">Log Out</button>
            </form>
        </div>
        @endauth

    </nav>
</header>

<!-- Theme toggle event listener -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('theme', {
            isDark: document.documentElement.classList.contains('dark'),
            toggle() {
                this.isDark = !this.isDark;
                if (this.isDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            }
        });
    });

    window.addEventListener('toggle-theme', () => {
        if (window.Alpine && Alpine.store('theme')) {
            Alpine.store('theme').toggle();
        } else {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }
    });
</script>