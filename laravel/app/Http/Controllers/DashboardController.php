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
        $baseLogQuery = LogEntry::whereIn('server_id', $serverIds);
        
        $totalLogs = (clone $baseLogQuery)->count();
        $recentLogs = (clone $baseLogQuery)
            ->with(['server', 'aiAnalysis'])
            ->orderBy('occurred_at', 'desc')
            ->take(12)
            ->get();
            
        $errorLogsCount = (clone $baseLogQuery)
            ->whereIn('level', ['error', 'critical', 'fatal'])
            ->count();

        $warningLogsCount = (clone $baseLogQuery)
            ->whereIn('level', ['warning', 'warn'])
            ->count();

        $infoLogsCount = (clone $baseLogQuery)
            ->whereIn('level', ['info', 'debug', 'notice'])
            ->count();

        $aiAnalysesCount = (clone $baseLogQuery)
            ->whereHas('aiAnalysis')
            ->count();

        $analysisCoverage = $totalLogs > 0 ? (int) round(($aiAnalysesCount / $totalLogs) * 100) : 0;
        $healthScore = $totalLogs > 0
            ? max(0, 100 - min(55, $errorLogsCount * 8) - min(25, $warningLogsCount * 3))
            : 100;

        $levelCounts = [
            'critical' => (clone $baseLogQuery)->whereIn('level', ['critical', 'fatal'])->count(),
            'error' => (clone $baseLogQuery)->where('level', 'error')->count(),
            'warning' => $warningLogsCount,
            'info' => $infoLogsCount,
        ];

        $topServers = Server::where('user_id', $user->id)
            ->withCount([
                'logEntries as logs_count',
                'logEntries as error_logs_count' => fn ($query) => $query->whereIn('level', ['error', 'critical', 'fatal']),
            ])
            ->orderByDesc('logs_count')
            ->take(4)
            ->get();

        return view('dashboard', compact(
            'serversCount',
            'totalLogs',
            'recentLogs',
            'errorLogsCount',
            'warningLogsCount',
            'analysisCoverage',
            'healthScore',
            'levelCounts',
            'topServers'
        ));
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
