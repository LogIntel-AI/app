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
            $content = '';

            // If the URL matches the local logs endpoint, read directly to avoid deadlock and auth issues
            if (Str::contains($server->log_pull_url, '/logs') && (Str::contains($server->log_pull_url, request()->getHost()) || Str::contains($server->log_pull_url, 'localhost'))) {
                $logFile = storage_path('logs/laravel.log');
                if (file_exists($logFile)) {
                    // Read only the last 100KB to avoid memory limits and slow processing
                    $fileSize = filesize($logFile);
                    $offset = max(0, $fileSize - 100000);
                    $content = file_get_contents($logFile, false, null, $offset);
                } else {
                    return back()->withErrors(['log_pull_url' => 'Local log file not found.']);
                }
            } else {
                $response = \Illuminate\Support\Facades\Http::timeout(10)->get($server->log_pull_url);
                if ($response->successful()) {
                    $content = $response->body();
                } else {
                    return back()->withErrors(['log_pull_url' => 'Failed to pull logs. Server responded with ' . $response->status()]);
                }
            }

            if (empty(trim($content))) {
                return back()->with('status', 'Log file is empty.');
            }

            // Fast parsing without catastrophic backtracking
            $pattern = '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.([A-Z]+): /m';
            if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                $count = count($matches[0]);
                // To avoid overwhelming DB/AI, take only the last 15 log entries
                $startIndex = max(0, $count - 15);
                $logsProcessed = 0;

                for ($i = $startIndex; $i < $count; $i++) {
                    $timestamp = $matches[1][$i][0];
                    $env = $matches[2][$i][0];
                    $level = strtolower($matches[3][$i][0]);
                    
                    $messageStart = $matches[0][$i][1] + strlen($matches[0][$i][0]);
                    $messageEnd = ($i + 1 < $count) ? $matches[0][$i + 1][1] : strlen($content);
                    
                    $message = trim(substr($content, $messageStart, $messageEnd - $messageStart));
                    
                    // Limit raw log size to prevent massive DB bloat from stack traces
                    if (strlen($message) > 5000) {
                        $message = substr($message, 0, 5000) . "\n...[truncated]";
                    }
                    
                    $raw_log = "[$timestamp] $env.$level: $message";
                    
                    // Summarize message for the title
                    $short_message = explode("\n", $message)[0];
                    if (strlen($short_message) > 200) {
                        $short_message = substr($short_message, 0, 200) . '...';
                    }

                    $log = $server->logEntries()->create([
                        'level' => $level,
                        'message' => 'Pulled: ' . $short_message,
                        'raw_log' => $raw_log,
                        'category' => 'external_pull',
                        'source' => 'url',
                        'occurred_at' => \Carbon\Carbon::parse($timestamp),
                    ]);

                    // Dispatch AI Analysis for errors
                    if (in_array($level, ['error', 'warning', 'critical', 'fatal'])) {
                        \App\Jobs\AnalyzeLogJob::dispatch($log);
                    }
                    
                    $logsProcessed++;
                }
                return back()->with('status', 'Successfully pulled and parsed ' . $logsProcessed . ' Laravel logs!');
            } else {
                // Fallback for non-Laravel logs: just store the last 2000 chars
                if (strlen($content) > 2000) {
                    $content = substr($content, -2000);
                }

                $log = $server->logEntries()->create([
                    'level' => 'info',
                    'message' => 'Pulled external logs from ' . parse_url($server->log_pull_url, PHP_URL_HOST),
                    'raw_log' => $content,
                    'category' => 'external_pull',
                    'source' => 'url',
                    'occurred_at' => now(),
                ]);

                \App\Jobs\AnalyzeLogJob::dispatch($log);

                return back()->with('status', 'Logs pulled and queued for AI analysis!');
            }
        } catch (\Exception $e) {
            return back()->withErrors(['log_pull_url' => 'Error reaching the URL: ' . $e->getMessage()]);
        }
    }
}
