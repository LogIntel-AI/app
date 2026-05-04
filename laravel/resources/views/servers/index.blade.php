<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Manage Servers & API Keys') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12 relative z-10 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
            
            <!-- Add New Server Form -->
            <div class="w-full md:w-1/3">
                <div class="bg-gray-800/80 backdrop-blur-lg border border-gray-700 shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-100 mb-4">Register New Server</h3>
                    <p class="text-sm text-gray-400 mb-6">Create a new server configuration to generate an API key for log ingestion.</p>
                    
                    <form action="{{ route('servers.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block font-medium text-sm text-gray-300">Server Name</label>
                            <input id="name" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="name" required placeholder="e.g. Production API" />
                        </div>
                        
                        <div class="mb-4">
                            <label for="environment" class="block font-medium text-sm text-gray-300">Environment</label>
                            <select id="environment" name="environment" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="production">Production</option>
                                <option value="staging">Staging</option>
                                <option value="development">Development</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="log_pull_url" class="block font-medium text-sm text-gray-300">Log Pull URL (Optional)</label>
                            <input id="log_pull_url" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="url" name="log_pull_url" placeholder="https://your-app.com/logs.txt" />
                            <p class="text-xs text-gray-500 mt-1">If provided, you can click a button to instantly fetch logs from this URL.</p>
                        </div>

                        <x-primary-button class="w-full justify-center">
                            {{ __('Generate API Key') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>

            <!-- Server List -->
            <div class="w-full md:w-2/3">
                <div class="bg-gray-800/80 backdrop-blur-lg border border-gray-700 shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-100 mb-6">Your Registered Servers</h3>
                    
                    @if(session('status'))
                        <div class="mb-4 font-medium text-sm text-green-400 bg-green-900/30 p-3 rounded-lg border border-green-800">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if($errors->has('log_pull_url'))
                        <div class="mb-4 font-medium text-sm text-red-400 bg-red-900/30 p-3 rounded-lg border border-red-800">
                            {{ $errors->first('log_pull_url') }}
                        </div>
                    @endif

                    <div class="space-y-4">
                        @forelse($servers as $server)
                            <div class="bg-gray-900/50 border border-gray-700 p-4 rounded-lg flex flex-col justify-between items-start gap-4 hover:border-gray-600 transition">
                                <div class="flex items-center gap-3 w-full">
                                    <span class="bg-gray-800 p-2 rounded-lg text-xl">🖥️</span>
                                    <div>
                                        <h4 class="font-bold text-gray-200">{{ $server->name }}</h4>
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-900/50 text-blue-300 border border-blue-800">{{ ucfirst($server->environment) }}</span>
                                    </div>
                                    <div class="ml-auto">
                                        <form action="{{ route('servers.destroy', $server) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this server? All its logs will be deleted too.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="w-full bg-black/40 p-3 rounded border border-gray-800 flex items-center justify-between">
                                    <div class="font-mono text-sm text-green-400 break-all select-all">
                                        {{ $server->api_token }}
                                    </div>
                                    <button onclick="navigator.clipboard.writeText('{{ $server->api_token }}'); alert('API Key copied!')" class="ml-4 text-gray-400 hover:text-white transition flex-shrink-0" title="Copy to clipboard">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 w-full">Send POST requests to <code class="text-purple-400">{{ url('/api/logs/ingest') }}</code></p>
                                
                                @if($server->log_pull_url)
                                    <div class="w-full pt-3 mt-1 border-t border-gray-800 flex items-center justify-between">
                                        <div class="text-xs text-gray-400 truncate pr-4">
                                            URL: <a href="{{ $server->log_pull_url }}" target="_blank" class="text-blue-400 hover:underline">{{ $server->log_pull_url }}</a>
                                        </div>
                                        <form action="{{ route('servers.pull', $server) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded transition flex-shrink-0">
                                                Pull Logs Now
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                You haven't registered any servers yet. Register one to get an API key.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
