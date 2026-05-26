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
            html {
                scroll-behavior: smooth;
            }
            .fade-bg {
                position: absolute;
                inset: 0;
                z-index: 0;
                opacity: 0.8;
                background-size: cover;
                background-position: center;
            }
            .glass-card {
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
            }
            
            /* Scroll reveal animations */
            .reveal {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.8s ease-out;
            }
            .reveal.active {
                opacity: 1;
                transform: translateY(0);
            }
            .reveal-left {
                opacity: 0;
                transform: translateX(-50px);
                transition: all 0.8s ease-out;
            }
            .reveal-left.active {
                opacity: 1;
                transform: translateX(0);
            }
            .reveal-right {
                opacity: 0;
                transform: translateX(50px);
                transition: all 0.8s ease-out;
            }
            .reveal-right.active {
                opacity: 1;
                transform: translateX(0);
            }
        </style>
    </head>
    <body class="antialiased font-sans bg-[#f7fdf5] dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-500">
        
        <!-- Backgrounds -->
        <div class="fixed inset-0 z-0 pointer-events-none fade-bg mix-blend-multiply dark:hidden transition-opacity duration-700" style="background-image: url('{{ asset('images/landing_bg.png') }}');"></div>
        <div class="fixed inset-0 z-0 pointer-events-none fade-bg mix-blend-screen hidden dark:block transition-opacity duration-700" style="background-image: url('{{ asset('images/landing_bg_dark.png') }}'); opacity: 0.6;"></div>
        <div id="landing-three-container" class="fixed inset-0 z-0 pointer-events-none opacity-40 dark:opacity-60"></div>

        <div class="relative z-10 flex flex-col min-h-screen">
            <!-- Navigation -->
            <nav class="w-full px-6 py-4 flex justify-between items-center glass-card bg-white/60 dark:bg-slate-950/60 border-b border-lime-100/50 dark:border-emerald-900/50 sticky top-0 z-50 shadow-sm shadow-lime-900/5 dark:shadow-emerald-900/20 transition-colors duration-500">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="LogIntel Logo" class="h-10 w-10 rounded-xl shadow-md border border-lime-100 dark:border-emerald-800" />
                    <span class="text-xl font-bold tracking-tight text-slate-800 dark:text-white">LogIntel.AI</span>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <button onclick="toggleTheme()" class="group relative flex h-10 w-10 items-center justify-center rounded-full border border-lime-200 bg-white shadow-sm transition hover:scale-105 hover:border-teal-400 dark:border-emerald-800 dark:bg-slate-900 dark:hover:border-emerald-400" title="Toggle Command Theme">
                        <!-- Dark mode icon: Terminal Prompt / Node -->
                        <svg class="hidden h-5 w-5 text-emerald-400 drop-shadow-[0_0_8px_rgba(52,211,153,0.8)] dark:block transition-all group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <!-- Light mode icon: Server Stack -->
                        <svg class="block h-5 w-5 text-teal-600 dark:hidden transition-all group-hover:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 4 3h8c2.5 0 4-1 4-3V7c0-2-1.5-3-4-3H8C5.5 4 4 5 4 7zm0 5h16M8 7h.01M8 12h.01M8 17h.01"></path>
                        </svg>
                    </button>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-medium text-slate-600 hover:text-teal-600 dark:text-slate-300 dark:hover:text-emerald-400 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="font-medium text-sm sm:text-base text-slate-600 hover:text-teal-600 dark:text-slate-300 dark:hover:text-emerald-400 transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 text-sm sm:text-base bg-gradient-to-r from-teal-500 to-lime-500 hover:from-teal-600 hover:to-lime-600 dark:from-emerald-600 dark:to-teal-500 dark:hover:from-emerald-500 dark:hover:to-teal-400 text-white font-semibold rounded-full transition shadow-lg shadow-teal-500/20 dark:shadow-emerald-500/20 transform hover:-translate-y-0.5">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>

            <!-- Hero Section -->
            <main class="flex-grow flex flex-col items-center justify-center px-6 py-28 text-center min-h-[90vh]">
                <div class="reveal">
                    <span class="px-4 py-1.5 mb-8 inline-block text-sm font-bold text-teal-800 bg-teal-100/60 dark:text-emerald-200 dark:bg-emerald-900/40 rounded-full border border-teal-200 dark:border-emerald-700 backdrop-blur-sm shadow-sm transition-colors">
                        <span class="inline-block w-2 h-2 mr-2 bg-teal-500 dark:bg-emerald-400 rounded-full animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                        Next-Gen Server Monitoring
                    </span>
                    <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-extrabold tracking-tight mb-8 text-slate-900 dark:text-white max-w-5xl leading-[1.1] transition-colors">
                        Stop reading logs.<br/> Start <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-lime-500 dark:from-emerald-400 dark:to-teal-300">understanding</span> them.
                    </h1>
                    <p class="text-xl md:text-2xl text-slate-600 dark:text-slate-300 mb-12 max-w-3xl mx-auto leading-relaxed transition-colors">
                        LogIntel.AI ingests your server noise and uses AI to instantly diagnose crashes, timeouts, and anomalies. Never manually grep an error log again.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center w-full sm:w-auto">
                        <a href="{{ route('register') }}" class="px-8 py-4 text-lg font-bold text-white bg-slate-900 hover:bg-teal-700 dark:bg-white dark:text-slate-900 dark:hover:bg-emerald-100 rounded-full transition shadow-xl shadow-slate-900/20 dark:shadow-emerald-900/30 transform hover:-translate-y-1">Start for free</a>
                        <a href="#how-it-works" class="px-8 py-4 text-lg font-bold text-slate-800 bg-white/80 hover:bg-white border border-slate-200 dark:text-slate-200 dark:bg-slate-800/80 dark:hover:bg-slate-800 dark:border-slate-700 rounded-full transition shadow-sm transform hover:-translate-y-1 glass-card">See how it works</a>
                    </div>
                </div>
            </main>

            <!-- Feature 1: The Dashboard -->
            <section id="how-it-works" class="py-24 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="flex flex-col lg:flex-row items-center gap-16">
                        <div class="flex-1 reveal-left">
                            <h2 class="text-teal-600 dark:text-emerald-400 font-bold tracking-wider uppercase text-sm mb-2 transition-colors">Centralized Command</h2>
                            <h3 class="text-4xl font-extrabold text-slate-900 dark:text-white mb-6 transition-colors">A clear view into the chaos</h3>
                            <p class="text-lg text-slate-600 dark:text-slate-300 mb-8 leading-relaxed transition-colors">
                                Stream your server logs into our lightning-fast interface. Filter by severity, search instantly, and watch as high-priority issues are automatically flagged. The clean, modern dashboard ensures you spot critical failures the second they happen.
                            </p>
                            <ul class="space-y-4 text-slate-700 dark:text-slate-300 transition-colors">
                                <li class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-lime-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Live ingestion engine
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-lime-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Color-coded severity tags
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-lime-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Aggregated trend graphs
                                </li>
                            </ul>
                        </div>
                        <div class="flex-1 w-full reveal-right">
                            <div class="relative rounded-2xl p-2 bg-gradient-to-br from-teal-100 to-lime-100 dark:from-emerald-900/60 dark:to-teal-900/60 shadow-2xl transition-colors">
                                <img src="{{ asset('images/app_preview_1.png') }}" alt="Dashboard interface" class="rounded-xl w-full object-cover shadow-sm border border-white/50 dark:border-white/10 dark:opacity-90" />
                                <div class="absolute -bottom-6 -left-6 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-xl border border-slate-100 dark:border-slate-700 glass-card animate-bounce transition-colors" style="animation-duration: 4s;">
                                    <div class="flex items-center gap-4">
                                        <div class="bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 p-3 rounded-full transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white transition-colors">Critical Error</div>
                                            <div class="text-sm text-slate-500 dark:text-slate-400 transition-colors">Spotted instantly</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Feature 2: AI Diagnosis -->
            <section class="py-24 relative overflow-hidden bg-white/40 dark:bg-slate-900/40 glass-card border-y border-lime-100/50 dark:border-emerald-900/50 transition-colors">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="flex flex-col-reverse lg:flex-row items-center gap-16">
                        <div class="flex-1 w-full reveal-left">
                            <div class="relative rounded-2xl p-2 bg-gradient-to-bl from-teal-100 to-emerald-100 dark:from-emerald-900/60 dark:to-teal-900/60 shadow-2xl transition-colors">
                                <img src="{{ asset('images/app_preview_2.png') }}" alt="AI Diagnosis Interface" class="rounded-xl w-full object-cover shadow-sm border border-white/50 dark:border-white/10 dark:opacity-90" />
                            </div>
                        </div>
                        <div class="flex-1 reveal-right">
                            <h2 class="text-teal-600 dark:text-emerald-400 font-bold tracking-wider uppercase text-sm mb-2 transition-colors">Automated Intelligence</h2>
                            <h3 class="text-4xl font-extrabold text-slate-900 dark:text-white mb-6 transition-colors">Let AI fix your servers</h3>
                            <p class="text-lg text-slate-600 dark:text-slate-300 mb-8 leading-relaxed transition-colors">
                                When a critical error hits, LogIntel doesn't just show you the stack trace. Our integrated AI analyzes the exact error message against your configuration and provides a human-readable diagnosis and actionable steps to resolve the problem.
                            </p>
                            <div class="space-y-6">
                                <div class="bg-white/70 dark:bg-slate-800/70 p-6 rounded-2xl border border-teal-100 dark:border-emerald-800 shadow-sm transition hover:shadow-md hover:scale-[1.02]">
                                    <h4 class="font-bold text-slate-900 dark:text-white mb-2 transition-colors">Instant Root Cause</h4>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm transition-colors">Turns cryptic "Connection refused (111)" errors into plain English: "Your Redis cache server is unreachable on port 6379."</p>
                                </div>
                                <div class="bg-white/70 dark:bg-slate-800/70 p-6 rounded-2xl border border-teal-100 dark:border-emerald-800 shadow-sm transition hover:shadow-md hover:scale-[1.02]">
                                    <h4 class="font-bold text-slate-900 dark:text-white mb-2 transition-colors">Actionable Solutions</h4>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm transition-colors">Generates direct shell commands or configuration changes you can apply immediately to restore service.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Bottom CTA -->
            <section class="py-28 text-center px-6">
                <div class="max-w-4xl mx-auto reveal">
                    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-6 transition-colors">Ready to regain control?</h2>
                    <p class="text-xl text-slate-600 dark:text-slate-300 mb-10 transition-colors">Setup takes less than 5 minutes. Generate your API key, curl a test log, and watch the AI take over.</p>
                    <a href="{{ route('register') }}" class="inline-block px-10 py-5 text-xl font-bold text-white bg-gradient-to-r from-teal-600 to-lime-500 hover:from-teal-700 hover:to-lime-600 dark:from-emerald-600 dark:to-teal-500 dark:hover:from-emerald-500 dark:hover:to-teal-400 rounded-full transition shadow-2xl shadow-teal-500/30 dark:shadow-emerald-500/30 transform hover:scale-105">Create your free account</a>
                </div>
            </section>

            <!-- Footer -->
            <footer class="py-12 text-center text-slate-500 dark:text-slate-400 glass-card border-t border-lime-100/50 dark:border-emerald-900/50 bg-white/30 dark:bg-slate-900/30 transition-colors">
                <p>&copy; {{ date('Y') }} LogIntel.AI. All rights reserved.</p>
            </footer>
        </div>

        <!-- Initialize specific landing page Three.js -->
        @vite(['resources/js/three-landing.js'])
        
        <!-- Scroll Reveal Script -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
                
                const revealObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('active');
                        } else {
                            entry.target.classList.remove('active');
                        }
                    });
                }, {
                    root: null,
                    threshold: 0.15,
                    rootMargin: "0px"
                });
                
                reveals.forEach(reveal => {
                    revealObserver.observe(reveal);
                });
            });
        </script>
    </body>
</html>
