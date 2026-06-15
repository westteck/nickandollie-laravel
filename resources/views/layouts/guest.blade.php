<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Nick & Ollie'))</title>

        <!-- Theme CSS variables -->
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
            }
        </style>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="/css/style.css">
        @yield('styles')
    </head>
    <body>
        <div class="auth-page">
            <div class="auth-container">
                @yield('content')
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        @yield('scripts')
    </body>
</html>
