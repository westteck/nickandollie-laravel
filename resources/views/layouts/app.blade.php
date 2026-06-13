<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#8b7355">
    <title>@yield('title', 'Nick &amp; Ollie Fortune')</title>
    <meta name="description" content="@yield('meta_description', 'Wedding photo sharing site for Nick & Ollie Fortune.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--brand:#8b7355;--brand-2:#d4c4b0;--bg:#faf8f5;--ink:#2f2a26}
        *{box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,sans-serif;background:var(--bg);color:var(--ink);line-height:1.5}
        a{color:inherit;text-decoration:none}
        .skip-link{position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden}
        .skip-link:focus{left:1rem;top:1rem;width:auto;height:auto;z-index:1000;background:#fff;padding:.75rem 1rem;border-radius:.75rem}
        .shell{min-height:100vh;display:flex;flex-direction:column}
        .nav{position:sticky;top:0;z-index:50;background:rgba(250,248,245,.92);backdrop-filter:blur(12px);border-bottom:1px solid rgba(139,115,85,.14)}
        .nav-inner,.footer-inner{max-width:1120px;margin:0 auto;padding:1rem}
        .nav-top{display:flex;align-items:center;justify-content:space-between;gap:1rem}
        .brand{font-weight:800;letter-spacing:.01em;color:var(--brand)}
        .nav-links{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.75rem}
        .nav-links a{padding:.55rem .85rem;border-radius:999px;background:#fff;border:1px solid rgba(139,115,85,.15);font-size:.95rem}
        main{flex:1}
        .footer{margin-top:auto;background:#3b312a;color:#fff}
        .footer a{color:#f5e7d4}
    </style>
</head>
<body>
<div class="shell">
    <a class="skip-link" href="#main-content">Skip to content</a>
    <header class="nav">
        <div class="nav-inner">
            <div class="nav-top">
                <a class="brand" href="{{ route('home') }}">Nick &amp; Ollie Fortune</a>
                <div style="font-size:.9rem;color:#6f6258">Wedding rebuild</div>
            </div>
            <nav class="nav-links" aria-label="Primary">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('gallery') }}">Gallery</a>
                <a href="{{ route('contest') }}">Contest</a>
                <a href="{{ route('upload') }}">Upload</a>
                <a href="{{ route('phonebook') }}">Phonebook</a>
            </nav>
        </div>
    </header>
    <main id="main-content">@yield('content')</main>
    <footer class="footer">
        <div class="footer-inner">
            <strong>Nick &amp; Ollie Fortune</strong>
            <p style="margin:.5rem 0 0;font-size:.95rem;max-width:60ch">Mobile-first Laravel port in progress. Legacy mail notifier settings still need mapping to Laravel mail config before cutover.</p>
        </div>
    </footer>
</div>
</body>
</html>
