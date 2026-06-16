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

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💒</text></svg>">

        <!-- Google Fonts: Playfair Display, Source Sans 3, Great Vibes -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Source+Sans+3:wght@400;500;600&family=Great+Vibes&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <!-- Moons & Stars theme (built from resources/css/app.css per design.md) -->
        <link rel="stylesheet" href="/css/app.css">

        @yield('styles')
        @stack('styles')

        @yield('scripts')
        @stack('scripts')
    </head>
    <body>

        <!-- Floating decorative blobs (glassmorphism backdrop) -->
        <div class="floating-blob" data-position="top-left" aria-hidden="true"></div>
        <div class="floating-blob" data-position="bottom-right" aria-hidden="true"></div>

        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="container py-4">
                <h1 class="section-header">{{ $header }}</h1>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="content-wrapper">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>

        <!-- Footer -->
        <footer class="site-footer mt-auto">
            <div class="container py-4">
                <p class="text-center mb-0">
                    &copy; {{ date('Y') }} Nick &amp; Ollie Fortune. All rights reserved.
                </p>
            </div>
        </footer>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
