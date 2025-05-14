<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as LaravelLog;
// Ensure the Log model exists in the specified namespace
use App\Models\Logs as LogModel; // Verify this path or update to the correct namespace

class LogsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Avoid logging log viewing or logging routes to prevent loops
        if ($request->is('logs*')) {
            return $next($request);
        }

        // Log to file
        LaravelLog::info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
        ]);

        // Log to database
        LogModel::create([
            'user_id'      => Auth::check() ? Auth::id() : null,
            'url'          => $request->path(),
            'full_url'     => $request->fullUrl(),
            'ip_address'   => $request->ip(),
            'mac_address'  => null, // capture this from frontend if needed
            'request_data' => json_encode($request->except(['password', '_token'])),
        ]);

        return $next($request);
    }
}
