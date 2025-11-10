<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<script>try{document.documentElement.setAttribute('data-theme',localStorage.getItem('theme')||'light')}catch(e){}</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Servora') }}</title>
    <script>
        
    </script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="preload" as="image" href="{{ asset('images/login.jpg') }}">
    <link rel="preload" as="image" href="{{ asset('images/register.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page font-sans antialiased">
    <header class="auth-topbar"><a class="auth-brand" href="{{ route('home') }}">{{ config('app.name', 'Servora') }}</a><a class="text-sm link" href="{{ route('services') }}">Explore services</a></header>
    <div class="auth-stage bg-base-200 flex items-start md:items-center justify-center px-3 py-4 sm:px-4 sm:py-6">
        <div class="w-full max-w-6xl">
            {{ $slot }}
        </div>
    </div>
</body>
</html>