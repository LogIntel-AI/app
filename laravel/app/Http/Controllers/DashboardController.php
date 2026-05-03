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
}
