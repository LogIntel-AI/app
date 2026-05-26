<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LogIntel.AI') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            function toggleTheme() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            }
        </script>
    </head>
    <body class="font-sans antialiased text-slate-950 dark:text-slate-100 bg-[#f4f7f4] dark:bg-[#080b10] transition-colors duration-300">
        <div class="min-h-screen relative overflow-hidden">
            <div class="fixed inset-0 z-0 pointer-events-none">
                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(244,247,244,0.98)_0%,rgba(232,238,234,0.94)_52%,rgba(219,231,226,0.92)_100%)] dark:bg-[linear-gradient(180deg,rgba(8,11,16,0.98)_0%,rgba(11,18,24,0.98)_48%,rgba(6,12,16,1)_100%)]"></div>
                <img src="{{ asset('images/bg.png') }}" alt="" class="absolute right-[-8rem] top-12 hidden h-[38rem] w-[38rem] rounded-[2rem] object-cover opacity-[0.08] grayscale lg:block dark:opacity-[0.18] dark:grayscale-0">
                <div class="absolute inset-0 dashboard-grid opacity-[0.42] dark:opacity-[0.2]"></div>
                <div id="three-container" class="absolute inset-0 opacity-70 dark:opacity-85"></div>
            </div>
            
            <div class="relative z-10">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="border-b border-white/70 bg-white/55 shadow-sm shadow-slate-900/5 backdrop-blur-xl dark:border-white/10 dark:bg-slate-950/35 dark:shadow-black/20">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            </div>
        </div>
    </body>
</html>
