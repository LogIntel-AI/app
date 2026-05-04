<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LogEntry;
use App\Models\Server;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $serversCount = Server::where('user_id', $user->id)->count();
        $serverIds = Server::where('user_id', $user->id)->pluck('id');
        
        $totalLogs = LogEntry::whereIn('server_id', $serverIds)->count();
        $recentLogs = LogEntry::whereIn('server_id', $serverIds)
            ->with(['server', 'aiAnalysis'])
            ->orderBy('occurred_at', 'desc')
            ->take(10)
            ->get();
            
        $errorLogsCount = LogEntry::whereIn('server_id', $serverIds)
            ->whereIn('level', ['error', 'critical', 'fatal'])
            ->count();

        return view('dashboard', compact('serversCount', 'totalLogs', 'recentLogs', 'errorLogsCount'));
    }

    public function reanalyze(LogEntry $log)
    {
        // Ensure the log belongs to a server owned by the current user
        if ($log->server->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete existing analysis if any
        if ($log->aiAnalysis) {
            $log->aiAnalysis()->delete();
        }

        // Dispatch new analysis job
        \App\Jobs\AnalyzeLogJob::dispatch($log);

        return back()->with('status', 'Log queued for AI Re-Analysis!');
    }
}
