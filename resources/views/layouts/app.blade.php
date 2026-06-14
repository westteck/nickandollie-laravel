<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>
        @hasSection('meta_description')
            <meta name="description" content="@yield('meta_description')">
        @endif

        <!-- Theme CSS variables -->
        <style>
            :root {
                --color-primary: {{ DB::table('theme_settings')->value('primary') ?? '#8b7355' }};
                --color-secondary: {{ DB::table('theme_settings')->value('secondary') ?? '#d4c4b0' }};
                --color-accent: {{ DB::table('theme_settings')->value('accent') ?? '#c9a86c' }};
                --color-background: {{ DB::table('theme_settings')->value('background') ?? '#faf8f5' }};
                --color-text: {{ DB::table('theme_settings')->value('text') ?? '#3d3530' }};
            }
            body {
                background-color: var(--color-background);
                color: var(--color-text);
            }
        </style>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Bootstrap 5 JS (for tabs, modals, etc) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Yield for page-specific scripts -->
        @yield('scripts')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
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
        </div>
    </body>
</html>
