<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-white/70 bg-white/70 backdrop-blur-xl transition-colors duration-300 dark:border-white/10 dark:bg-slate-950/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex min-w-0 items-center">
                <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3">
                    <x-application-logo class="h-10 w-10 shrink-0" />
                    <div class="hidden min-w-0 sm:block">
                        <div class="text-sm font-bold text-slate-950 dark:text-white">LogIntel.AI</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Signal command center</div>
                    </div>
                </a>

                <div class="hidden items-center gap-1 sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-slate-950 text-white shadow-sm dark:bg-white dark:text-slate-950' : 'text-slate-600 hover:bg-white/80 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white' }} rounded-full px-4 py-2 text-sm font-semibold transition">
                        Dashboard
                    </a>
                    <a href="{{ route('servers.index') }}" class="{{ request()->routeIs('servers.index') ? 'bg-slate-950 text-white shadow-sm dark:bg-white dark:text-slate-950' : 'text-slate-600 hover:bg-white/80 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white' }} rounded-full px-4 py-2 text-sm font-semibold transition">
                        Servers
                    </a>
                </div>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                <button onclick="toggleTheme()" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white/80 text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-300 hover:text-teal-700 dark:border-white/10 dark:bg-white/10 dark:text-slate-200 dark:hover:border-teal-300/60 dark:hover:text-teal-200" title="Toggle theme">
                    <svg class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.36 6.36-.7-.7M6.34 6.34l-.7-.7m12.72 0-.7.7M6.34 17.66l-.7.7M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0z" />
                    </svg>
                    <svg class="h-5 w-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.35 15.35A9 9 0 0 1 8.65 3.65 9 9 0 1 0 20.35 15.35z" />
                    </svg>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 rounded-full border border-slate-200 bg-white/80 py-1.5 pe-3 ps-1.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-300 hover:text-slate-950 focus:outline-none dark:border-white/10 dark:bg-white/10 dark:text-slate-200 dark:hover:border-teal-300/60 dark:hover:text-white">
                            <span class="grid h-8 w-8 place-items-center rounded-full bg-teal-100 text-xs font-bold text-teal-800 dark:bg-teal-300/15 dark:text-teal-200">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="max-w-36 truncate">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center gap-2 sm:hidden">
                <button onclick="toggleTheme()" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white/80 text-slate-600 shadow-sm dark:border-white/10 dark:bg-white/10 dark:text-slate-200" title="Toggle theme">
                    <svg class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.36 6.36-.7-.7M6.34 6.34l-.7-.7m12.72 0-.7.7M6.34 17.66l-.7.7M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0z" />
                    </svg>
                    <svg class="h-5 w-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.35 15.35A9 9 0 0 1 8.65 3.65 9 9 0 1 0 20.35 15.35z" />
                    </svg>
                </button>
                <button @click="open = ! open" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white/80 text-slate-600 shadow-sm transition dark:border-white/10 dark:bg-white/10 dark:text-slate-200">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-200 bg-white/85 px-4 pb-4 pt-3 backdrop-blur-xl dark:border-white/10 dark:bg-slate-950/85 sm:hidden">
        <div class="space-y-2">
            <a href="{{ route('dashboard') }}" class="block rounded-xl px-3 py-2 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'text-slate-600 dark:text-slate-300' }}">
                Dashboard
            </a>
            <a href="{{ route('servers.index') }}" class="block rounded-xl px-3 py-2 text-sm font-semibold {{ request()->routeIs('servers.index') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'text-slate-600 dark:text-slate-300' }}">
                Servers
            </a>
        </div>

        <div class="mt-4 border-t border-slate-200 pt-4 dark:border-white/10">
            <div class="px-3">
                <div class="font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</div>
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="block rounded-xl px-3 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
