<header class="site-header">
    <nav class="nav-shell relative mx-auto flex w-full max-w-6xl items-center justify-between">

        <!-- Logo / Brand -->
        <a href="{{ Auth::check() ? route('gallery') : route('home') }}" class="nav-brand" aria-label="Nick & Ollie home">
            <span class="logo-text">
                <span class="logo-title">Nick &amp; Ollie</span>
            </span>
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
                <div class="nav-user" id="userDropdown">
                    <button class="nav-user-toggle" onclick="toggleUserDropdown()" aria-expanded="false" aria-haspopup="true">
                        {{ Auth::user()->guest_name ?? Auth::user()->name }}
                        <i class="fas fa-chevron-down ms-1"></i>
                    </button>
                    <div class="nav-dropdown" id="userDropdownMenu" style="display: none;">
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
                <a href="{{ route('login') }}" class="nav-cta">Login</a>
            @endauth

            @auth
            <button type="button" class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                <i class="fas fa-bars" id="mobileMenuIcon"></i>
            </button>
            @endauth
        </div>

        <!-- Mobile dropdown -->
        <div class="mobile-nav" id="mobileMenu" style="display: none;">
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

<script>
function toggleUserDropdown() {
    var menu = document.getElementById('userDropdownMenu');
    var btn = document.querySelector('.nav-user-toggle');
    var isOpen = menu.style.display !== 'none';
    menu.style.display = isOpen ? 'none' : 'block';
    btn.setAttribute('aria-expanded', !isOpen);
}
function toggleMobileMenu() {
    var menu = document.getElementById('mobileMenu');
    var icon = document.getElementById('mobileMenuIcon');
    var isOpen = menu.style.display !== 'none';
    menu.style.display = isOpen ? 'none' : 'block';
    icon.className = isOpen ? 'fas fa-bars' : 'fas fa-times';
}
document.addEventListener('click', function(e) {
    var userDropdown = document.getElementById('userDropdown');
    var userMenu = document.getElementById('userDropdownMenu');
    if (userDropdown && !userDropdown.contains(e.target)) {
        userMenu.style.display = 'none';
        document.querySelector('.nav-user-toggle')?.setAttribute('aria-expanded', 'false');
    }
});
</script>

<style>
/* Moons & Stars gallery (5×5 grid per request) */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.85rem;
    margin-bottom: 2rem;
}
@media (max-width: 1199px) { .gallery-grid { grid-template-columns: repeat(4, 1fr); } }
@media (max-width: 991px)  { .gallery-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 575px)  { .gallery-grid { grid-template-columns: repeat(2, 1fr); gap: 0.5rem; } }
.gallery-item {
    position: relative;
    aspect-ratio: 1 / 1;
    border-radius: 0.85rem;
    overflow: hidden;
    background: rgba(11, 16, 32, 0.6);
    border: 1px solid rgba(194, 184, 183, 0.2);
    cursor: pointer;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    display: block;
}
.gallery-item:hover {
    transform: translateY(-3px);
    border-color: rgba(194, 184, 183, 0.5);
    box-shadow: 0 18px 40px -18px rgba(0, 0, 0, 0.7);
}
.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.gallery-item .overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 50%, rgba(11, 16, 32, 0.7) 100%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 0.75rem;
    color: rgba(250, 235, 215, 0.85);
    opacity: 0;
    transition: opacity 0.25s ease;
}
.gallery-item:hover .overlay { opacity: 1; }

/* Moons & Stars input + card overrides (defeats Bootstrap defaults) */
.glass-panel {
    backdrop-filter: blur(24px) saturate(140%) !important;
    -webkit-backdrop-filter: blur(24px) saturate(140%) !important;
    background: rgba(11, 16, 32, 0.55) !important;
    border: 1px solid rgba(194, 184, 183, 0.25) !important;
    box-shadow: 0 30px 60px -25px rgba(0, 0, 0, 0.7) !important;
    color: #FAEBD7;
}
.glass-panel .form-label { color: rgba(250, 235, 215, 0.85); font-family: 'Source Sans 3', sans-serif; font-size: 0.85rem; font-weight: 500; letter-spacing: 0.04em; margin-bottom: 0.35rem; }
.glass-panel .form-control {
    background: rgba(11, 16, 32, 0.6) !important;
    border: 1px solid rgba(194, 184, 183, 0.3) !important;
    color: #FAEBD7 !important;
    font-family: 'Source Sans 3', sans-serif;
    border-radius: 0.75rem;
    padding: 0.65rem 0.9rem;
}
.glass-panel .form-control::placeholder { color: rgba(194, 184, 183, 0.55); }
.glass-panel .form-control:focus { background: rgba(11, 16, 32, 0.75) !important; border-color: #c2b8b7 !important; box-shadow: 0 0 0 3px rgba(194, 184, 183, 0.2) !important; }
.glass-panel .nav-tabs { border-bottom: 1px solid rgba(194, 184, 183, 0.2); }
.glass-panel .nav-tabs .nav-link { color: rgba(250, 235, 215, 0.6); border: 0; background: transparent; font-family: 'Source Sans 3', sans-serif; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; font-size: 0.78rem; padding: 0.65rem 1rem; }
.glass-panel .nav-tabs .nav-link.active { color: #FAEBD7; background: linear-gradient(135deg, #171d33, #36538f); border-radius: 9999px; }
.glass-panel .nav-tabs .nav-link:not(.active):hover { color: #c2b8b7; }

/* Moons & Stars nav (design.md §6) — minimal complement to app.css */
.site-header { position: sticky; top: 0; z-index: 50; padding: 1.25rem 0; }
.nav-shell {
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    background: rgba(11, 16, 32, 0.82);
    border: 1px solid rgba(94, 123, 166, 0.4);
    border-radius: 9999px;
    box-shadow: 0 18px 35px -25px rgba(0, 0, 0, 0.7);
    max-width: 1200px;
    margin: 0 auto;
    padding: 0.85rem 1.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    width: calc(100% - 3rem);
    position: relative;
}
.nav-brand {
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    padding: 0.55rem 1.1rem;
    background: linear-gradient(135deg, rgba(23, 29, 51, 0.3), rgba(54, 83, 143, 0.2));
    border: 1px solid rgba(194, 184, 183, 0.35);
    border-radius: 9999px;
    transition: transform 0.25s, box-shadow 0.25s;
}
.nav-brand:hover { transform: translateY(-2px); box-shadow: 0 12px 30px -20px rgba(0,0,0,0.6); }
.logo-text { letter-spacing: 0.52em; text-transform: uppercase; color: #FAEBD7; font-family: 'Playfair Display', serif; font-size: 1.15rem; }
.logo-text .logo-title { letter-spacing: 0.52em; }
.nav-links { display: flex; align-items: center; gap: 1.85rem; }
.nav-link {
    text-decoration: none;
    color: rgba(250, 235, 215, 0.7);
    letter-spacing: 0.28em;
    text-transform: uppercase;
    font-family: 'Source Sans 3', sans-serif;
    font-size: 0.75rem;
    font-weight: 600;
    position: relative;
    padding: 0.25rem 0;
    transition: color 0.2s, transform 0.2s;
}
.nav-link::after {
    content: '';
    position: absolute;
    left: 0; right: 0; bottom: -0.5rem;
    height: 3px;
    border-radius: 9999px;
    background: linear-gradient(90deg, transparent, rgba(194, 184, 183, 0.85), transparent);
    opacity: 0;
    transform: translateY(4px);
    transition: opacity 0.2s, transform 0.2s;
}
.nav-link:hover, .nav-link.active { color: #C2B8B7; transform: translateY(-1px); }
.nav-link:hover::after, .nav-link.active::after { opacity: 1; transform: translateY(0); }
.nav-actions { display: flex; align-items: center; gap: 1rem; }
.nav-user { position: relative; }
.nav-user-toggle {
    background: rgba(11, 16, 32, 0.6);
    border: 1px solid rgba(94, 123, 166, 0.5);
    color: #FAEBD7;
    font-family: 'Source Sans 3', sans-serif;
    font-size: 0.85rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    cursor: pointer;
    transition: border-color 0.2s, color 0.2s;
}
.nav-user-toggle:hover { border-color: #C2B8B7; color: #C2B8B7; }
.nav-dropdown {
    position: absolute; right: 0; top: calc(100% + 0.5rem);
    background: rgba(11, 16, 32, 0.95);
    border: 1px solid rgba(94, 123, 166, 0.4);
    border-radius: 1rem;
    box-shadow: 0 30px 60px -25px rgba(0,0,0,0.8);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    min-width: 220px;
    padding: 0.5rem;
    z-index: 200;
}
.nav-dropdown a, .nav-dropdown-link {
    display: block;
    padding: 0.55rem 0.85rem;
    color: rgba(250, 235, 215, 0.85);
    text-decoration: none;
    font-size: 0.85rem;
    border-radius: 0.5rem;
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}
.nav-dropdown a:hover, .nav-dropdown-link:hover { background: rgba(194, 184, 183, 0.15); color: #C2B8B7; }
.nav-dropdown hr { border: none; border-top: 1px solid rgba(94, 123, 166, 0.3); margin: 0.25rem 0; }
.nav-cta {
    text-decoration: none;
    color: #fff;
    background: linear-gradient(135deg, #171d33, #36538f);
    border-radius: 9999px;
    padding: 0.6rem 1.5rem;
    font-family: 'Source Sans 3', sans-serif;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.28em;
    text-transform: uppercase;
    box-shadow: 0 12px 30px -20px rgba(0,0,0,0.7);
    transition: transform 0.2s, box-shadow 0.2s;
}
.nav-cta:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 18px 35px -20px rgba(0,0,0,0.8); }
.mobile-menu-toggle {
    display: none;
    background: rgba(11, 16, 32, 0.6);
    border: 1px solid rgba(94, 123, 166, 0.4);
    color: #FAEBD7;
    width: 2.4rem; height: 2.4rem;
    border-radius: 9999px;
    cursor: pointer;
    align-items: center;
    justify-content: center;
}
.mobile-menu-toggle:hover { color: #C2B8B7; border-color: #C2B8B7; }
.mobile-nav {
    display: none;
    position: absolute;
    top: calc(100% + 1rem);
    left: 0; right: 0;
    background: rgba(11, 16, 32, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(94, 123, 166, 0.4);
    border-radius: 1.5rem;
    padding: 1.25rem;
    flex-direction: column;
    gap: 0.5rem;
    z-index: 40;
    box-shadow: 0 35px 80px -30px rgba(0,0,0,0.85);
}
.mobile-nav a, .nav-mobile-link {
    display: block;
    padding: 0.65rem 0.5rem;
    color: rgba(250, 235, 215, 0.85);
    text-decoration: none;
    border-bottom: 1px solid rgba(94, 123, 166, 0.25);
    font-family: 'Source Sans 3', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.26em;
    text-transform: uppercase;
    background: none;
    border-left: none;
    border-right: none;
    border-top: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}
.mobile-nav a:hover, .nav-mobile-link:hover { color: #C2B8B7; }
@media (max-width: 1023px) {
    .nav-links { display: none; }
    .nav-actions > .nav-user { display: none; }
    .mobile-menu-toggle { display: inline-flex; }
    .nav-shell { padding: 0.75rem 1.2rem; }
    .logo-text { font-size: 1rem; letter-spacing: 0.38em; }
}
</style>
