@php
    $healthTone = $healthScore >= 85
        ? ['label' => 'Stable', 'text' => 'text-emerald-700 dark:text-emerald-300', 'bg' => 'bg-emerald-500']
        : ($healthScore >= 60
            ? ['label' => 'Watch', 'text' => 'text-amber-700 dark:text-amber-300', 'bg' => 'bg-amber-500']
            : ['label' => 'Hot', 'text' => 'text-red-700 dark:text-red-300', 'bg' => 'bg-red-500']);

    $levelMeta = [
        'critical' => ['label' => 'Critical', 'count' => $levelCounts['critical'] ?? 0, 'bar' => 'bg-red-500', 'text' => 'text-red-700 dark:text-red-300'],
        'error' => ['label' => 'Errors', 'count' => $levelCounts['error'] ?? 0, 'bar' => 'bg-rose-500', 'text' => 'text-rose-700 dark:text-rose-300'],
        'warning' => ['label' => 'Warnings', 'count' => $levelCounts['warning'] ?? 0, 'bar' => 'bg-amber-500', 'text' => 'text-amber-700 dark:text-amber-300'],
        'info' => ['label' => 'Info', 'count' => $levelCounts['info'] ?? 0, 'bar' => 'bg-teal-500', 'text' => 'text-teal-700 dark:text-teal-300'],
    ];
    $maxLevelCount = max(array_map(fn ($level) => $level['count'], $levelMeta)) ?: 1;
    $latestInsight = $recentLogs->first(fn ($log) => $log->aiAnalysis);
    $insightLogs = $recentLogs->filter(fn ($log) => $log->aiAnalysis)->take(5);
    $lastLog = $recentLogs->first();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="mb-2 inline-flex items-center gap-2 rounded-full border border-teal-200 bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-800 backdrop-blur-md transition-all hover:scale-105 hover:bg-teal-100 cursor-default dark:border-teal-300/20 dark:bg-teal-300/10 dark:text-teal-200 dark:hover:bg-teal-300/20">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-teal-500 shadow-[0_0_14px_rgba(20,184,166,0.8)]"></span>
                    Live monitoring
                </div>
                <h1 class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-500 dark:from-teal-400 dark:to-emerald-300 sm:text-3xl">Log Intelligence Dashboard</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                    Triage server noise, spot critical signals, and re-run AI analysis without leaving the command center.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('servers.index') }}" class="inline-flex items-center gap-2 rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-slate-950/10 transition hover:-translate-y-0.5 hover:bg-teal-800 dark:bg-white dark:text-slate-950 dark:hover:bg-teal-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7zM8 20h8M12 16v4" />
                    </svg>
                    Manage servers
                </a>
                <div class="rounded-full border border-white/70 bg-white/70 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm backdrop-blur-md transition hover:bg-white/90 hover:shadow-md dark:border-white/10 dark:bg-white/10 dark:text-slate-200 dark:hover:bg-white/20">
                    Last signal: <span class="text-teal-600 dark:text-teal-400">{{ $lastLog ? ($lastLog->occurred_at ? $lastLog->occurred_at->diffForHumans() : $lastLog->created_at->diffForHumans()) : 'No logs yet' }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div x-data="{ panel: 'logs' }" class="relative z-10 py-6 sm:py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(360px,0.85fr)]">
                <div class="dashboard-panel overflow-hidden p-6 sm:p-8">
                    <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-2xl">
                            <p class="text-sm font-semibold text-teal-700 dark:text-teal-300">Operational overview</p>
                            <h2 class="mt-3 text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-600 dark:from-white dark:to-slate-400 sm:text-4xl">Your log stream is under watch.</h2>
                            <p class="mt-4 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                AI review coverage is at {{ $analysisCoverage }}%, with {{ $errorLogsCount }} critical or error-level signals across {{ $serversCount }} registered {{ \Illuminate\Support\Str::plural('server', $serversCount) }}.
                            </p>
                        </div>

                        <div class="min-w-[13rem] rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm dark:border-white/10 dark:bg-white/10">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-600 dark:text-slate-300">Health score</span>
                                <span class="{{ $healthTone['text'] }} text-sm font-bold">{{ $healthTone['label'] }}</span>
                            </div>
                            <div class="mt-3 flex items-end gap-2">
                                <span class="text-4xl font-bold text-slate-950 dark:text-white">{{ $healthScore }}</span>
                                <span class="mb-1 text-sm text-slate-500 dark:text-slate-400">/ 100</span>
                            </div>
                            <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-white/10">
                                <div class="{{ $healthTone['bg'] }} h-full rounded-full" style="width: {{ $healthScore }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="metric-card group">
                            <div class="flex items-center justify-between">
                                <span class="metric-icon bg-teal-100 text-teal-700 dark:bg-teal-300/10 dark:text-teal-200">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7zM8 20h8M12 16v4" />
                                    </svg>
                                </span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Active</span>
                            </div>
                            <div class="mt-5 text-3xl font-bold text-slate-950 dark:text-white">{{ $serversCount }}</div>
                            <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">Registered servers</div>
                        </div>

                        <div class="metric-card group">
                            <div class="flex items-center justify-between">
                                <span class="metric-icon bg-cyan-100 text-cyan-700 dark:bg-cyan-300/10 dark:text-cyan-200">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h10M4 17h16" />
                                    </svg>
                                </span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Indexed</span>
                            </div>
                            <div class="mt-5 text-3xl font-bold text-slate-950 dark:text-white">{{ number_format($totalLogs) }}</div>
                            <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">Total log entries</div>
                        </div>

                        <div class="metric-card group">
                            <div class="flex items-center justify-between">
                                <span class="metric-icon bg-red-100 text-red-700 dark:bg-red-300/10 dark:text-red-200">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 4.3 2.9 17a2 2 0 0 0 1.7 3h14.8a2 2 0 0 0 1.7-3L13.7 4.3a2 2 0 0 0-3.4 0z" />
                                    </svg>
                                </span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Priority</span>
                            </div>
                            <div class="mt-5 text-3xl font-bold text-slate-950 dark:text-white">{{ number_format($errorLogsCount) }}</div>
                            <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">Critical signals</div>
                        </div>

                        <div class="metric-card group">
                            <div class="flex items-center justify-between">
                                <span class="metric-icon bg-amber-100 text-amber-700 dark:bg-amber-300/10 dark:text-amber-200">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 3 4 14h7l-1 7 9-12h-7l1-6z" />
                                    </svg>
                                </span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">AI</span>
                            </div>
                            <div class="mt-5 text-3xl font-bold text-slate-950 dark:text-white">{{ $analysisCoverage }}%</div>
                            <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">Analysis coverage</div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-visual group">
                    <img src="{{ asset('images/bg.png') }}" alt="Log management command interface" class="absolute inset-0 h-full w-full object-cover opacity-80 mix-blend-overlay transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/80 to-transparent"></div>
                    <div class="signal-scan"></div>
                    <div class="relative flex h-full min-h-[24rem] flex-col justify-end p-6">
                        <div class="mb-4 inline-flex w-fit items-center gap-2 rounded-full border border-white/15 bg-black/35 px-3 py-1 text-xs font-semibold text-teal-100 backdrop-blur">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            Analyzer online
                        </div>
                        <div class="max-w-sm">
                            <h3 class="text-2xl font-bold text-white">Signal radar</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-200">A live visual layer for ingestion, severity spikes, and AI triage movement.</p>
                        </div>
                        <div class="mt-5 grid grid-cols-3 gap-3">
                            <div class="rounded-2xl border border-white/10 bg-white/10 p-3 text-white backdrop-blur">
                                <div class="text-xs text-slate-300">Warnings</div>
                                <div class="mt-1 text-xl font-bold">{{ number_format($warningLogsCount) }}</div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/10 p-3 text-white backdrop-blur">
                                <div class="text-xs text-slate-300">Servers</div>
                                <div class="mt-1 text-xl font-bold">{{ number_format($serversCount) }}</div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/10 p-3 text-white backdrop-blur">
                                <div class="text-xs text-slate-300">AI</div>
                                <div class="mt-1 text-xl font-bold">{{ $analysisCoverage }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-[minmax(0,1.65fr)_minmax(320px,0.85fr)]">
                <div class="dashboard-panel overflow-hidden">
                    <div class="flex flex-col gap-4 border-b border-slate-200 p-5 dark:border-white/10 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">Signal stream</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Recent log events with AI context and quick re-analysis.</p>
                        </div>

                        <div class="inline-flex w-fit rounded-full border border-slate-200 bg-slate-100 p-1 dark:border-white/10 dark:bg-white/10">
                            <button type="button" @click="panel = 'logs'" :class="panel === 'logs' ? 'bg-white text-slate-950 shadow-sm dark:bg-slate-950 dark:text-white' : 'text-slate-500 dark:text-slate-300'" class="rounded-full px-4 py-2 text-sm font-semibold transition">
                                Logs
                            </button>
                            <button type="button" @click="panel = 'insights'" :class="panel === 'insights' ? 'bg-white text-slate-950 shadow-sm dark:bg-slate-950 dark:text-white' : 'text-slate-500 dark:text-slate-300'" class="rounded-full px-4 py-2 text-sm font-semibold transition">
                                AI insights
                            </button>
                        </div>
                    </div>

                    <div x-show="panel === 'logs'" x-cloak class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse ($recentLogs as $log)
                            @php
                                $level = strtolower((string) $log->level);
                                $levelClass = match (true) {
                                    in_array($level, ['critical', 'fatal']) => 'border-red-200 bg-red-50 text-red-700 dark:border-red-400/20 dark:bg-red-400/10 dark:text-red-200',
                                    $level === 'error' => 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-400/20 dark:bg-rose-400/10 dark:text-rose-200',
                                    in_array($level, ['warning', 'warn']) => 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-400/20 dark:bg-amber-400/10 dark:text-amber-200',
                                    default => 'border-teal-200 bg-teal-50 text-teal-700 dark:border-teal-400/20 dark:bg-teal-400/10 dark:text-teal-200',
                                };
                            @endphp

                            <article class="log-row group">
                                <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="{{ $levelClass }} rounded-full border px-3 py-1 text-xs font-bold">
                                                {{ strtoupper($log->level ?? 'INFO') }}
                                            </span>
                                            <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $log->server->name ?? 'Unknown server' }}</span>
                                            <span class="text-sm text-slate-400">/</span>
                                            <span class="text-sm text-slate-500 dark:text-slate-400">{{ $log->occurred_at ? $log->occurred_at->diffForHumans() : $log->created_at->diffForHumans() }}</span>
                                        </div>

                                        <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-700 dark:text-slate-300" title="{{ $log->message }}">
                                            {{ $log->message }}
                                        </p>

                                        @if($log->aiAnalysis)
                                            <div class="mt-4 rounded-2xl border border-teal-200 bg-teal-50/70 p-4 dark:border-teal-300/15 dark:bg-teal-300/10">
                                                <div class="mb-2 flex items-center gap-2 text-xs font-bold text-teal-800 dark:text-teal-200">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 3 4 14h7l-1 7 9-12h-7l1-6z" />
                                                    </svg>
                                                    {{ $log->aiAnalysis->category ?? 'AI insight' }}
                                                </div>
                                                <p class="text-sm leading-6 text-slate-700 dark:text-slate-200">{{ $log->aiAnalysis->summary }}</p>
                                                @if($log->aiAnalysis->suggestion)
                                                    <p class="mt-3 border-t border-teal-200 pt-3 text-sm leading-6 text-slate-600 dark:border-teal-300/15 dark:text-slate-300">
                                                        <span class="font-bold text-emerald-700 dark:text-emerald-300">Suggestion:</span>
                                                        {{ $log->aiAnalysis->suggestion }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <form action="{{ route('logs.reanalyze', $log) }}" method="POST" class="shrink-0">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-300 hover:text-teal-800 dark:border-white/10 dark:bg-white/10 dark:text-slate-200 dark:hover:border-teal-300/60 dark:hover:text-teal-100" title="Run AI analysis again">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M5 15a7 7 0 0 0 12 3M19 9A7 7 0 0 0 7 6" />
                                            </svg>
                                            Re-analyze
                                        </button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <div class="px-6 py-16 text-center">
                                <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-teal-100 text-teal-700 dark:bg-teal-300/10 dark:text-teal-200">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h10M4 17h16" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-bold text-slate-950 dark:text-white">No logs received yet</h3>
                                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500 dark:text-slate-400">Register a server and send logs to the ingestion endpoint to light up this stream.</p>
                                <a href="{{ route('servers.index') }}" class="mt-5 inline-flex items-center rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white dark:bg-white dark:text-slate-950">
                                    Create a server key
                                </a>
                            </div>
                        @endforelse
                    </div>

                    <div x-show="panel === 'insights'" x-cloak class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse($insightLogs as $log)
                            <article class="log-row">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full border border-teal-200 bg-teal-50 px-3 py-1 text-xs font-bold text-teal-800 dark:border-teal-300/15 dark:bg-teal-300/10 dark:text-teal-200">
                                        {{ $log->aiAnalysis->severity ?? 'AI' }}
                                    </span>
                                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $log->server->name ?? 'Unknown server' }}</span>
                                </div>
                                <h3 class="mt-3 text-base font-bold text-slate-950 dark:text-white">{{ $log->aiAnalysis->category ?? 'AI diagnosis' }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $log->aiAnalysis->summary }}</p>
                                @if($log->aiAnalysis->suggestion)
                                    <p class="mt-3 rounded-2xl bg-slate-100 p-4 text-sm leading-6 text-slate-600 dark:bg-white/10 dark:text-slate-300">{{ $log->aiAnalysis->suggestion }}</p>
                                @endif
                            </article>
                        @empty
                            <div class="px-6 py-16 text-center">
                                <h3 class="text-lg font-bold text-slate-950 dark:text-white">No AI insights yet</h3>
                                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500 dark:text-slate-400">Error, warning, critical, and fatal logs will queue analysis when an AI key is configured.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="dashboard-panel p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-950 dark:text-white">Level mix</h2>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Severity spread across stored logs.</p>
                            </div>
                            <span class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-bold text-slate-600 dark:border-white/10 dark:bg-white/10 dark:text-slate-300">{{ number_format($totalLogs) }} total</span>
                        </div>

                        <div class="mt-6 space-y-5">
                            @foreach($levelMeta as $level)
                                @php
                                    $barWidth = $level['count'] > 0 ? max(8, round(($level['count'] / $maxLevelCount) * 100)) : 0;
                                @endphp
                                <div>
                                    <div class="mb-2 flex items-center justify-between text-sm">
                                        <span class="{{ $level['text'] }} font-bold">{{ $level['label'] }}</span>
                                        <span class="font-semibold text-slate-600 dark:text-slate-300">{{ number_format($level['count']) }}</span>
                                    </div>
                                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-200 dark:bg-white/10">
                                        <div class="{{ $level['bar'] }} h-full rounded-full transition-all duration-700" style="width: {{ $barWidth }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="dashboard-panel p-5">
                        <h2 class="text-lg font-bold text-slate-950 dark:text-white">Noisiest servers</h2>
                        <div class="mt-5 space-y-3">
                            @forelse($topServers as $server)
                                <div class="rounded-2xl border border-slate-200 bg-white/70 p-4 dark:border-white/10 dark:bg-white/10">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="truncate text-sm font-bold text-slate-900 dark:text-white">{{ $server->name }}</div>
                                            <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ ucfirst($server->environment) }} environment</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($server->logs_count) }}</div>
                                            <div class="text-xs text-red-600 dark:text-red-300">{{ number_format($server->error_logs_count) }} errors</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="rounded-2xl border border-dashed border-slate-300 p-4 text-sm text-slate-500 dark:border-white/15 dark:text-slate-400">Servers will appear here after registration.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="dashboard-panel terminal-panel overflow-hidden p-5">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-slate-950 dark:text-white">AI pulse</h2>
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-400 shadow-[0_0_18px_rgba(52,211,153,0.9)]"></span>
                        </div>
                        <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-950 p-4 font-mono text-xs leading-6 text-teal-100 shadow-inner shadow-black/30 dark:border-white/10">
                            <div><span class="text-emerald-300">coverage</span> = {{ $analysisCoverage }}%</div>
                            <div><span class="text-amber-300">warnings</span> = {{ number_format($warningLogsCount) }}</div>
                            <div><span class="text-rose-300">critical</span> = {{ number_format($errorLogsCount) }}</div>
                            <div><span class="text-cyan-300">latest_ai</span> = {{ $latestInsight ? ($latestInsight->aiAnalysis->category ?? 'ready') : 'waiting' }}</div>
                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </div>
</x-app-layout>
