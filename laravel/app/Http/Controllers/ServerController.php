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
        ]);

        $server = new Server();
        $server->user_id = Auth::id();
        $server->name = $request->name;
        $server->environment = $request->environment;
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
}
