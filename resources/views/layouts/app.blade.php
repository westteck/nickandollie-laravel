<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

        <!-- Theme CSS variables from DB -->
        <style>
            :root {
                --primary: #8b7355;
                --primary-dark: #6b5744;
                --secondary: #d4c4b0;
                --accent: #c9a86c;
                --bg: #faf8f5;
                --text: #3d3530;
                --text-light: #7a726a;
                --white: #ffffff;
                --error: #c45c5c;
                --success: #5c9c6b;
                --gold: #e8b923;
            }
        </style>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <!-- Legacy CSS (full design system) -->
        <link rel="stylesheet" href="/css/style.css">

        <!-- Page-specific scripts -->
        @yield('scripts')
    </head>
    <body>

        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="container py-4">
                <h1 class="section-header">{{ $header }}</h1>
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
            <div class="container py-4">
                <p class="text-center text-muted small mb-0">
                    &copy; {{ date('Y') }} Nick &amp; Ollie Fortune. All rights reserved.
                </p>
            </div>
        </footer>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
