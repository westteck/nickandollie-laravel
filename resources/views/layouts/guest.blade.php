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

        <!-- Background -->
        <div class="fixed inset-0 -z-10" style="background-image: url('/back.jpg'); background-size: cover; background-position: center;">
            <div class="absolute inset-0" style="background-color: rgba(23,29,51,0.30);"></div>
        </div>

        <!-- Floating blobs -->
        <div class="floating-blob" data-position="top-left"></div>
        <div class="floating-blob" data-position="bottom-right"></div>

        <!-- Content -->
        <div class="content-wrapper flex flex-col min-h-screen">
            <main class="flex-1 flex flex-col justify-center py-12">
                <div class="mx-auto w-full max-w-md px-4">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="mt-auto">
            <div class="mx-auto max-w-6xl px-6 py-4">
                <p class="text-center text-xs text-body/50">
                    &copy; {{ date('Y') }} Nick &amp; Ollie Fortune. All rights reserved.
                </p>
            </div>
        </footer>

    </body>
</html>