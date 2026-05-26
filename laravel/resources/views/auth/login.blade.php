<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flip-container {{ request()->routeIs('register') ? 'flip' : '' }}" id="auth-card">
        <div class="flipper" style="min-height: 580px;">
            
            <!-- FRONT: LOGIN -->
            <div class="front bg-white/80 dark:bg-slate-900/80 glass-panel shadow-2xl rounded-3xl border border-lime-100 dark:border-emerald-800/50 p-8 flex flex-col justify-center transition-colors duration-500">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Welcome back</h2>
                    <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm">Enter your credentials to access your dashboard</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="login_email" class="block font-medium text-sm text-slate-700 dark:text-slate-300">Email</label>
                        <input id="login_email" class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 focus:border-teal-500 focus:ring-teal-500 dark:text-white transition" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-5">
                        <div class="flex justify-between items-center">
                            <label for="login_password" class="block font-medium text-sm text-slate-700 dark:text-slate-300">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-sm text-teal-600 dark:text-emerald-400 hover:text-teal-800 dark:hover:text-emerald-300 transition" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <input id="login_password" class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 focus:border-teal-500 focus:ring-teal-500 dark:text-white transition" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-5">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-slate-300 dark:border-slate-700 text-teal-600 shadow-sm focus:ring-teal-500 dark:bg-slate-800/50" name="remember">
                            <span class="ms-2 text-sm text-slate-600 dark:text-slate-400">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex flex-col mt-6 gap-4">
                        <button class="w-full justify-center px-4 py-3 bg-slate-900 dark:bg-white border border-transparent rounded-xl font-bold text-white dark:text-slate-900 hover:bg-teal-700 dark:hover:bg-emerald-100 focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-slate-900/20 dark:shadow-emerald-900/20 transform hover:-translate-y-0.5">
                            Sign in
                        </button>
                        
                        <div class="relative flex items-center justify-center w-full py-2">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 text-slate-500 bg-[#f7fdf5] dark:bg-slate-900 rounded-lg">Or continue with</span>
                            </div>
                        </div>

                        <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm transform hover:-translate-y-0.5">
                            <svg class="h-5 w-5 mr-3" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span class="font-semibold text-slate-700 dark:text-slate-200">Google</span>
                        </a>
                        
                        <div class="text-center text-sm text-slate-500 dark:text-slate-400">
                            Don't have an account? 
                            <button type="button" onclick="toggleAuthMode()" class="font-bold text-teal-600 dark:text-emerald-400 hover:text-teal-800 dark:hover:text-emerald-300 transition">Sign up</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- BACK: SIGNUP -->
            <div class="back bg-white/80 dark:bg-slate-900/80 glass-panel shadow-2xl rounded-3xl border border-lime-100 dark:border-emerald-800/50 p-8 flex flex-col justify-center transition-colors duration-500">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Create an account</h2>
                    <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm">Start understanding your logs in seconds</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block font-medium text-sm text-slate-700 dark:text-slate-300">Name</label>
                        <input id="name" class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 focus:border-teal-500 focus:ring-teal-500 dark:text-white transition" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <label for="register_email" class="block font-medium text-sm text-slate-700 dark:text-slate-300">Email</label>
                        <input id="register_email" class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 focus:border-teal-500 focus:ring-teal-500 dark:text-white transition" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <label for="register_password" class="block font-medium text-sm text-slate-700 dark:text-slate-300">Password</label>
                        <input id="register_password" class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 focus:border-teal-500 focus:ring-teal-500 dark:text-white transition" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <label for="password_confirmation" class="block font-medium text-sm text-slate-700 dark:text-slate-300">Confirm Password</label>
                        <input id="password_confirmation" class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 focus:border-teal-500 focus:ring-teal-500 dark:text-white transition" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex flex-col mt-6 gap-4">
                        <button class="w-full justify-center px-4 py-3 bg-gradient-to-r from-teal-500 to-lime-500 hover:from-teal-600 hover:to-lime-600 dark:from-emerald-600 dark:to-teal-500 dark:hover:from-emerald-500 dark:hover:to-teal-400 border border-transparent rounded-xl font-bold text-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition shadow-lg shadow-teal-500/20 dark:shadow-emerald-500/20 transform hover:-translate-y-0.5">
                            Sign up
                        </button>

                        <div class="relative flex items-center justify-center w-full py-2">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 text-slate-500 bg-[#f7fdf5] dark:bg-slate-900 rounded-lg">Or continue with</span>
                            </div>
                        </div>

                        <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm transform hover:-translate-y-0.5">
                            <svg class="h-5 w-5 mr-3" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span class="font-semibold text-slate-700 dark:text-slate-200">Google</span>
                        </a>

                        <div class="text-center text-sm text-slate-500 dark:text-slate-400">
                            Already have an account? 
                            <button type="button" onclick="toggleAuthMode()" class="font-bold text-teal-600 dark:text-emerald-400 hover:text-teal-800 dark:hover:text-emerald-300 transition">Sign in</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleAuthMode() {
            const card = document.getElementById('auth-card');
            card.classList.toggle('flip');
            
            // Seamlessly update the URL without reloading to reflect the current state
            const isRegister = card.classList.contains('flip');
            window.history.pushState({}, '', isRegister ? '/register' : '/login');
            
            // Adjust height if necessary depending on content size
            const flipper = document.querySelector('.flipper');
            if(isRegister) {
                flipper.style.minHeight = '740px';
            } else {
                flipper.style.minHeight = '580px';
            }
        }
        
        // Initialize height correctly based on starting state
        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('auth-card').classList.contains('flip')) {
                document.querySelector('.flipper').style.minHeight = '740px';
            } else {
                document.querySelector('.flipper').style.minHeight = '580px';
            }
        });
    </script>
</x-guest-layout>
