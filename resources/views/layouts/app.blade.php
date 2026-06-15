<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Nick & Ollie'))</title>
        @hasSection('meta_description')
            <meta name="description" content="@yield('meta_description')">
        @endif

        <!-- Theme CSS variables -->
        <style>
            :root {
                --color-primary: #171d33;
                --color-accent: #c2b8b7;
                --color-body: #FAEBD7;
                --color-sec: #36538f;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Dark mode init (prevent flash) -->
        <script>
            if (localStorage.getItem('theme') === 'light' ||
                (!localStorage.getItem('theme') && !document.documentElement.classList.contains('dark'))) {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Page-specific scripts -->
        @yield('scripts')
    </head>
    <body class="flex flex-col min-h-screen">

        <!-- Floating background blobs -->
        <div class="floating-blob" data-position="top-left"></div>
        <div class="floating-blob" data-position="bottom-right"></div>

        <!-- Content wrapper -->
        <div class="content-wrapper">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="glass-panel mx-auto mt-6 max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
                    <div class="font-display text-2xl font-semibold text-night">{{ $header }}</div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>

            <!-- Footer -->
            <footer class="mt-auto">
                <div class="mx-auto max-w-6xl px-6 py-6">
                    <div class="glass-panel flex flex-col items-center gap-4 p-4 md:flex-row md:justify-between">
                        @auth
                        <a href="{{ route('gallery') }}" class="nav-brand footer-nav-brand" aria-label="Nick &amp; Ollie home">
                            <div class="logo-text font-display"><span class="logo-title">Nick &amp; Ollie</span></div>
                        </a>
                        @endauth
                        <p class="text-xs text-body/70">
                            &copy; {{ date('Y') }} Nick &amp; Ollie Fortune. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>

    </body>
</html>