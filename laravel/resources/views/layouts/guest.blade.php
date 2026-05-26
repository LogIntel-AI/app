<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LogIntel.AI') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        
        <style>
            /* 3D Flip Card Styles */
            .flip-container {
                perspective: 1200px;
                width: 100%;
            }
            .flipper {
                transition: transform 0.8s cubic-bezier(0.4, 0.2, 0.2, 1);
                transform-style: preserve-3d;
                position: relative;
                width: 100%;
            }
            .flip-container.flip .flipper {
                transform: rotateY(180deg);
            }
            .front, .back {
                backface-visibility: hidden;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }
            .front {
                z-index: 2;
                transform: rotateY(0deg);
            }
            .back {
                transform: rotateY(180deg);
            }
            .fade-bg {
                position: absolute;
                inset: 0;
                z-index: 0;
                opacity: 0.8;
                background-size: cover;
                background-position: center;
            }
            .glass-panel {
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
            }
        </style>
    </head>
    <body class="antialiased font-sans bg-[#f7fdf5] dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-500">
        
        <!-- Backgrounds -->
        <div class="fixed inset-0 z-0 pointer-events-none fade-bg mix-blend-multiply dark:hidden transition-opacity duration-700" style="background-image: url('{{ asset('images/landing_bg.png') }}');"></div>
        <div class="fixed inset-0 z-0 pointer-events-none fade-bg mix-blend-screen hidden dark:block transition-opacity duration-700" style="background-image: url('{{ asset('images/landing_bg_dark.png') }}'); opacity: 0.6;"></div>
        
        <!-- Three.js Container for Auth (uses the dashboard three-bg.js via app.js) -->
        <div id="three-container" class="fixed inset-0 z-0 pointer-events-none opacity-50 dark:opacity-70"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10 px-4">
            <div class="mb-8 transform transition hover:scale-105">
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="LogIntel Logo" class="h-24 w-24 rounded-2xl shadow-xl shadow-lime-900/10 dark:shadow-emerald-900/30 border border-lime-100 dark:border-emerald-800" />
                </a>
            </div>

            <div class="w-full sm:max-w-md w-full">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
