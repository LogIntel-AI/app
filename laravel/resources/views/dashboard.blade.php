<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Server Health Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Servers Count -->
                <div class="bg-gray-800/80 backdrop-blur-lg border border-gray-700 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-gray-100 flex items-center">
                        <div class="text-3xl text-blue-400 mr-4">🖥️</div>
                        <div>
                            <div class="text-sm text-gray-400 uppercase tracking-wide">Active Servers</div>
                            <div class="text-3xl font-bold">{{ $serversCount }}</div>
                        </div>
                    </div>
                </div>

                <!-- Total Logs -->
                <div class="bg-gray-800/80 backdrop-blur-lg border border-gray-700 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-gray-100 flex items-center">
                        <div class="text-3xl text-purple-400 mr-4">📊</div>
                        <div>
                            <div class="text-sm text-gray-400 uppercase tracking-wide">Total Logs Analyzed</div>
                            <div class="text-3xl font-bold">{{ $totalLogs }}</div>
                        </div>
                    </div>
                </div>

                <!-- Error Logs -->
                <div class="bg-gray-800/80 backdrop-blur-lg border border-gray-700 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-gray-100 flex items-center">
                        <div class="text-3xl text-red-400 mr-4">⚠️</div>
                        <div>
                            <div class="text-sm text-gray-400 uppercase tracking-wide">Critical Errors</div>
                            <div class="text-3xl font-bold">{{ $errorLogsCount }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Logs Table -->
            <div class="bg-gray-800/80 backdrop-blur-lg border border-gray-700 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-100">Recent Server Logs</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Server</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Level</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Message</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">AI Insight</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800/30 divide-y divide-gray-700">
                            @forelse ($recentLogs as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $log->occurred_at ? $log->occurred_at->diffForHumans() : $log->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $log->server->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ strtolower($log->level) === 'error' ? 'bg-red-900 text-red-200' : 
                                               (strtolower($log->level) === 'warning' ? 'bg-yellow-900 text-yellow-200' : 'bg-green-900 text-green-200') }}">
                                            {{ strtoupper($log->level) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300 max-w-xs truncate" title="{{ $log->message }}">
                                        {{ Str::limit($log->message, 50) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300">
                                        @if($log->aiAnalysis)
                                            <span class="text-purple-400 text-xs flex items-center" title="{{ $log->aiAnalysis->summary }}">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                {{ Str::limit($log->aiAnalysis->summary ?? $log->aiAnalysis->category, 30) }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 text-xs">No analysis yet</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">
                                        No logs found. Add a server and start sending logs via API.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
