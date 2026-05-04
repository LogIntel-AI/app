<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LogIntel.AI | Intelligent Server Monitoring</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            // Theme setup
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
        <style>
            .fade-bg {
                position: absolute;
                inset: 0;
                z-index: 0;
                opacity: 0.15;
                background-size: cover;
                background-position: center;
                mix-blend-mode: luminosity;
            }
            .glass-card {
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }
            /* Carousel specific styles */
            .carousel-inner { transition: transform 0.5s ease; }
        </style>
    </head>
    <body class="antialiased font-sans bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-300">
        <!-- Interactive Backgrounds -->
        <div id="three-container" class="fixed inset-0 z-0 pointer-events-none opacity-60"></div>
        <div class="fixed inset-0 z-0 pointer-events-none fade-bg dark:opacity-20 opacity-5" style="background-image: url('{{ asset('images/bg.png') }}');"></div>

        <div class="relative z-10 flex flex-col min-h-screen">
            <!-- Navigation -->
            <nav class="w-full px-6 py-4 flex justify-between items-center glass-card bg-white/70 dark:bg-gray-900/70 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-50">
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span class="text-lg sm:text-xl font-bold tracking-tight">LogIntel.AI</span>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <button onclick="toggleTheme()" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition">
                        <svg class="w-5 h-5 hidden dark:block text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg class="w-5 h-5 block dark:hidden text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="font-medium text-sm sm:text-base text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition shadow-lg shadow-blue-500/30">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>

            <!-- Hero Section -->
            <main class="flex-grow flex flex-col items-center justify-center px-6 py-20 text-center animate-slide-up">
                <span class="px-4 py-1.5 mb-6 text-sm font-semibold text-blue-600 bg-blue-100 dark:text-blue-400 dark:bg-blue-900/30 rounded-full border border-blue-200 dark:border-blue-800">Next-Gen Server Monitoring</span>
                <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tight mb-6 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 max-w-4xl">
                    Stop reading logs.<br/> Start <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400">understanding</span> them.
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-400 mb-10 max-w-2xl leading-relaxed">
                    LogIntel.AI analyzes your server logs in real-time, instantly diagnosing crashes and anomalies so you don't have to manually dig through errors.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 text-lg font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-xl shadow-blue-500/40 transform hover:-translate-y-1">Start for free</a>
                    <a href="#how-it-works" class="w-full sm:w-auto px-8 py-4 text-lg font-bold text-gray-800 bg-white hover:bg-gray-50 border border-gray-200 dark:text-white dark:bg-gray-800 dark:hover:bg-gray-700 dark:border-gray-700 rounded-xl transition transform hover:-translate-y-1">See how it works</a>
                </div>
            </main>

            <!-- How it works Carousel Section -->
            <section id="how-it-works" class="py-24 bg-white/50 dark:bg-gray-900/50 glass-card">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">How LogIntel.AI Works</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400">Three simple steps to secure, intelligent monitoring.</p>
                    </div>

                    <div class="relative overflow-hidden rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-2xl" id="carousel-container">
                        <div class="carousel-inner flex w-full pb-10 sm:pb-0" id="carousel">
                            <!-- Slide 1 -->
                            <div class="w-full flex-shrink-0 flex flex-col md:flex-row items-center p-6 md:p-16 gap-6 md:gap-8">
                                <div class="flex-1 text-center md:text-left">
                                    <div class="w-12 h-12 mx-auto md:mx-0 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-xl font-bold mb-4 md:mb-6">1</div>
                                    <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">Generate Your API Key</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-base sm:text-lg">Create an account and register your server in our dashboard. You'll instantly receive a unique, secure API Key. This key allows your server to securely push logs directly to our ingestion engine.</p>
                                </div>
                                <div class="flex-1 w-full bg-white dark:bg-gray-900 p-4 sm:p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-inner overflow-hidden">
                                    <pre class="text-xs sm:text-sm text-gray-800 dark:text-gray-300 font-mono overflow-x-auto whitespace-pre-wrap word-break"><code>Server Token: 
logintel_sk_7812y4uh12ui41...

Endpoint: 
POST https://your-domain.com/api/logs/ingest</code></pre>
                                </div>
                            </div>
                            <!-- Slide 2 -->
                            <div class="w-full flex-shrink-0 flex flex-col md:flex-row items-center p-6 md:p-16 gap-6 md:gap-8">
                                <div class="flex-1 text-center md:text-left">
                                    <div class="w-12 h-12 mx-auto md:mx-0 bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 rounded-full flex items-center justify-center text-xl font-bold mb-4 md:mb-6">2</div>
                                    <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">Send Your Logs</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-base sm:text-lg">Use a simple cron job, curl script, or your application's logging library (like Monolog) to forward your error logs to our endpoint with the API token attached.</p>
                                </div>
                                <div class="flex-1 w-full bg-white dark:bg-gray-900 p-4 sm:p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-inner overflow-hidden">
                                    <pre class="text-xs sm:text-sm text-green-600 dark:text-green-400 font-mono overflow-x-auto whitespace-pre-wrap word-break"><code>{
  "level": "error",
  "message": "Connection refused",
  "timestamp": "2026-05-03 10:42:11"
}</code></pre>
                                </div>
                            </div>
                            <!-- Slide 3 -->
                            <div class="w-full flex-shrink-0 flex flex-col md:flex-row items-center p-6 md:p-16 gap-6 md:gap-8">
                                <div class="flex-1 text-center md:text-left">
                                    <div class="w-12 h-12 mx-auto md:mx-0 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center text-xl font-bold mb-4 md:mb-6">3</div>
                                    <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">AI Diagnosis & Alerts</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-base sm:text-lg">Our background OpenAI engine instantly analyzes the error, categorizes it, explains exactly what went wrong, and suggests a fix on your dashboard.</p>
                                </div>
                                <div class="flex-1 w-full bg-white dark:bg-gray-900 p-4 sm:p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-inner overflow-hidden">
                                    <pre class="text-xs sm:text-sm text-purple-600 dark:text-purple-400 font-mono overflow-x-auto whitespace-pre-wrap word-break"><code>Analysis:
Database server is down. 
Suggestion: Check port 5432 and firewall.</code></pre>
                                </div>
                            </div>
                        </div>

                        <!-- Carousel Controls -->
                        <div class="absolute bottom-6 left-0 right-0 flex justify-center gap-2">
                            <button onclick="setSlide(0)" class="w-3 h-3 rounded-full bg-gray-400 dark:bg-gray-600 hover:bg-blue-500 transition slide-dot"></button>
                            <button onclick="setSlide(1)" class="w-3 h-3 rounded-full bg-gray-400 dark:bg-gray-600 hover:bg-blue-500 transition slide-dot"></button>
                            <button onclick="setSlide(2)" class="w-3 h-3 rounded-full bg-gray-400 dark:bg-gray-600 hover:bg-blue-500 transition slide-dot"></button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="py-8 text-center text-gray-500 dark:text-gray-400 glass-card border-t border-gray-200 dark:border-gray-800">
                <p>&copy; {{ date('Y') }} LogIntel.AI. All rights reserved.</p>
            </footer>
        </div>

        <script>
            // Carousel Logic
            let currentSlide = 0;
            const carousel = document.getElementById('carousel');
            const dots = document.querySelectorAll('.slide-dot');

            function updateCarousel() {
                carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
                dots.forEach((dot, index) => {
                    if(index === currentSlide) {
                        dot.classList.remove('bg-gray-400', 'dark:bg-gray-600');
                        dot.classList.add('bg-blue-600', 'w-6');
                    } else {
                        dot.classList.add('bg-gray-400', 'dark:bg-gray-600');
                        dot.classList.remove('bg-blue-600', 'w-6');
                    }
                });
            }

            function setSlide(index) {
                currentSlide = index;
                updateCarousel();
            }

            // Auto advance
            setInterval(() => {
                currentSlide = (currentSlide + 1) % 3;
                updateCarousel();
            }, 5000);

            // Init
            updateCarousel();
        </script>
        
        <!-- Import Three JS logic -->
        <script type="module">
            import { initThreeJS } from '{{ Vite::asset('resources/js/three-bg.js') }}';
            initThreeJS('three-container');
        </script>
    </body>
</html>
