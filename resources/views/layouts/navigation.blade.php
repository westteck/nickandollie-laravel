<header class="site-nav">
    <nav class="nav-container" x-data="{ mobileOpen: false }">

        <!-- Logo / Brand -->
        <a href="{{ Auth::check() ? route('gallery') : route('home') }}" class="nav-brand" aria-label="Nick & Ollie home">
            <span class="nav-brand-text">{{ Auth::check() ? 'Nick & Ollie' : 'Welcome' }}</span>
        </a>

        <!-- Desktop links -->
        <div class="nav-links">
            <a class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Gallery</a>
            <a class="nav-link {{ request()->routeIs('contest') ? 'active' : '' }}" href="{{ route('contest') }}">Contests</a>
            <a class="nav-link {{ request()->routeIs('phonebook') ? 'active' : '' }}" href="{{ route('phonebook') }}">Phonebook</a>
            @auth
            <a class="nav-link {{ request()->routeIs('upload') ? 'active' : '' }}" href="{{ route('upload') }}">Upload</a>
            @if(auth()->user()->is_admin)
                <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Admin</a>
            @endif
            @endauth
        </div>

        <!-- Right side -->
        <div class="nav-actions">
            @auth
                <!-- User dropdown -->
                <div class="nav-user" x-data="{ open: false }">
                    <button @click="open = !open" class="nav-user-toggle">
                        {{ Auth::user()->guest_name ?? Auth::user()->name }}
                        <i class="fas fa-chevron-down ms-1"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="nav-dropdown">
                        <a href="{{ route('profile.edit') }}">Profile</a>
                        <a href="{{ route('wedding.profile', auth()->id()) }}">My Public Profile</a>
                        @if(auth()->user()->is_admin)
                            <hr>
                            <a href="{{ route('admin.users') }}"><i class="fas fa-users me-2"></i>Users</a>
                            <a href="{{ route('admin.photos') }}"><i class="fas fa-images me-2"></i>Photos</a>
                            <a href="{{ route('admin.comments') }}"><i class="fas fa-comments me-2"></i>Comments</a>
                            <a href="{{ route('admin.contests') }}"><i class="fas fa-trophy me-2"></i>Contests</a>
                            <a href="{{ route('admin.themes') }}"><i class="fas fa-palette me-2"></i>Themes</a>
                            <a href="{{ route('admin.phonebook') }}"><i class="fas fa-address-book me-2"></i>Phonebook</a>
                            <a href="{{ route('admin.settings') }}"><i class="fas fa-cog me-2"></i>Settings</a>
                        @endif
                        <hr>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-dropdown-link">Log Out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
            @endauth

            <!-- Mobile menu toggle -->
            @auth
            <button type="button" class="nav-mobile-toggle" x-on:click="mobileOpen = !mobileOpen" aria-label="Toggle menu">
                <i class="fas" :class="mobileOpen ? 'fa-times' : 'fa-bars'"></i>
            </button>
            @endauth
        </div>

        <!-- Mobile dropdown -->
        <div class="nav-mobile" x-show="mobileOpen" x-transition>
            <a href="{{ route('gallery') }}">Gallery</a>
            <a href="{{ route('contest') }}">Contests</a>
            <a href="{{ route('phonebook') }}">Phonebook</a>
            @auth
            <a href="{{ route('upload') }}">Upload</a>
            <a href="{{ route('profile.edit') }}">Profile</a>
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}">Admin</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-mobile-link">Log Out</button>
            </form>
            @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Create Account</a>
            @endauth
        </div>

    </nav>
</header>

<style>
.site-nav {
    background: var(--white);
    border-bottom: 2px solid var(--secondary);
    position: sticky;
    top: 0;
    z-index: 100;
}
.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 56px;
}
.nav-brand {
    text-decoration: none;
    color: var(--primary);
    font-size: 1.25rem;
    font-weight: 600;
    letter-spacing: 0.05em;
}
.nav-brand-text {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.nav-links {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.nav-link {
    text-decoration: none;
    color: var(--text-light);
    font-size: 0.85rem;
    font-weight: 500;
    padding: 0.4rem 0.75rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
}
.nav-link:hover, .nav-link.active {
    color: var(--primary);
    background: rgba(139, 115, 85, 0.08);
}
.nav-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.nav-user {
    position: relative;
}
.nav-user-toggle {
    background: none;
    border: none;
    color: var(--text);
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    padding: 0.4rem 0.75rem;
    border-radius: 0.5rem;
    transition: background 0.2s;
}
.nav-user-toggle:hover {
    background: rgba(139, 115, 85, 0.08);
}
.nav-dropdown {
    position: absolute;
    right: 0;
    top: 100%;
    margin-top: 0.5rem;
    background: var(--white);
    border: 1px solid var(--secondary);
    border-radius: 0.75rem;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    min-width: 200px;
    padding: 0.5rem;
    z-index: 200;
}
.nav-dropdown a, .nav-dropdown-link {
    display: block;
    padding: 0.5rem 0.75rem;
    color: var(--text);
    text-decoration: none;
    font-size: 0.85rem;
    border-radius: 0.5rem;
    transition: background 0.2s;
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}
.nav-dropdown a:hover, .nav-dropdown-link:hover {
    background: rgba(139, 115, 85, 0.08);
    color: var(--primary);
}
.nav-dropdown hr {
    border: none;
    border-top: 1px solid var(--secondary);
    margin: 0.25rem 0;
}
.nav-mobile-toggle {
    display: none;
    background: none;
    border: none;
    color: var(--text);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
}
.nav-mobile {
    display: none;
    position: absolute;
    top: 56px;
    left: 0;
    right: 0;
    background: var(--white);
    border-bottom: 2px solid var(--secondary);
    padding: 0.5rem 1rem;
    z-index: 99;
}
.nav-mobile a, .nav-mobile-link {
    display: block;
    padding: 0.75rem 0;
    color: var(--text);
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    background: none;
    border-left: none;
    border-right: none;
    border-top: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}
.nav-mobile a:hover, .nav-mobile-link:hover {
    color: var(--primary);
}
@media (max-width: 768px) {
    .nav-links { display: none; }
    .nav-actions .nav-user { display: none; }
    .nav-mobile-toggle { display: block; }
    .nav-mobile { display: block; }
}
</style>
