<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Nick & Ollie'))</title>

        <!-- Google Fonts: Playfair Display, Source Sans 3, Great Vibes -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Source+Sans+3:wght@400;500;600&family=Great+Vibes&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <!-- Moons & Stars theme -->
        <link rel="stylesheet" href="/css/app.css">

        @yield('styles')
        @stack('styles')
    </head>
    <body class="auth-body">
        <div class="floating-blob" data-position="top-left" aria-hidden="true"></div>
        <div class="floating-blob" data-position="bottom-right" aria-hidden="true"></div>

        @yield('content')

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        @yield('scripts')
        @stack('scripts')
    </body>
</html>
