<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Server;
use App\Models\LogEntry;
use App\Jobs\AnalyzeLogJob;

class LogController extends Controller
{
    public function ingest(Request $request)
    {
        $request->validate([
            'level' => 'required|string',
            'message' => 'required|string',
            'source' => 'nullable|string',
            'timestamp' => 'nullable|date',
            'raw_log' => 'nullable|string',
            'category' => 'nullable|string',
        ]);

        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $server = Server::where('api_token', $token)->first();
        if (!$server) {
            return response()->json(['error' => 'Invalid API token'], 401);
        }

        $logEntry = $server->logEntries()->create([
            'level' => $request->level,
            'message' => $request->message,
            'source' => $request->source,
            'ip_address' => $request->ip(),
            'raw_log' => $request->raw_log ?? $request->message,
            'occurred_at' => $request->timestamp ? \Carbon\Carbon::parse($request->timestamp) : now(),
            'category' => $request->category,
        ]);

        // Dispatch AI analysis if it's an error, warning or critical log
        if (in_array(strtolower($logEntry->level), ['error', 'warning', 'critical', 'fatal'])) {
            AnalyzeLogJob::dispatch($logEntry);
        }

        return response()->json(['status' => 'success', 'log_id' => $logEntry->id]);
    }
}
