<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Server;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::where('user_id', Auth::id())->get();
        return view('servers.index', compact('servers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'environment' => 'required|string|in:production,staging,development',
            'log_pull_url' => 'nullable|url|max:2048',
        ]);

        $server = new Server();
        $server->user_id = Auth::id();
        $server->name = $request->name;
        $server->environment = $request->environment;
        $server->log_pull_url = $request->log_pull_url;
        $server->api_token = 'interlog_sk_' . Str::random(32);
        $server->save();

        return redirect()->route('servers.index')->with('status', 'Server generated successfully!');
    }

    public function destroy(Server $server)
    {
        if ($server->user_id !== Auth::id()) abort(403);
        $server->delete();
        return redirect()->route('servers.index')->with('status', 'Server deleted.');
    }

    public function pullLogs(Server $server)
    {
        if ($server->user_id !== Auth::id()) abort(403);
        if (!$server->log_pull_url) {
            return back()->withErrors(['log_pull_url' => 'No Pull URL configured for this server.']);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)->get($server->log_pull_url);
            
            if ($response->successful()) {
                $content = $response->body();
                
                // Keep only last 2000 characters to prevent overwhelming the DB/AI
                if (strlen($content) > 2000) {
                    $content = substr($content, -2000);
                }

                $log = $server->logs()->create([
                    'level' => 'info',
                    'message' => 'Pulled external logs from ' . parse_url($server->log_pull_url, PHP_URL_HOST),
                    'raw_log' => $content,
                    'category' => 'external_pull',
                    'source' => 'url',
                    'occurred_at' => now(),
                ]);

                // Dispatch AI Analysis
                \App\Jobs\AnalyzeLogJob::dispatch($log);

                return back()->with('status', 'Logs pulled and queued for AI analysis!');
            } else {
                return back()->withErrors(['log_pull_url' => 'Failed to pull logs. Server responded with ' . $response->status()]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['log_pull_url' => 'Error reaching the URL: ' . $e->getMessage()]);
        }
    }
}
