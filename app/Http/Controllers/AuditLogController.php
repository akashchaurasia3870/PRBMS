<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuditLogController extends Controller
{
    /**
     * Show all audit logs, with optional filtering by model/resource.
     */
    public function index(Request $request)
    {
        // Optional filters: model type, model id, user id, action
        $logs = AuditLog::query();

        if ($request->filled('model_type')) {
            // Accept either the full class name or shorthand, e.g. "inventory" or "App\Models\Inventory"
            $type = $request->input('model_type');
            if (!Str::startsWith($type, 'App\\Models\\')) {
                $type = 'App\\Models\\' . Str::studly($type);
            }
            $logs->where('auditable_type', $type);
        }
        if ($request->filled('model_id')) {
            $logs->where('auditable_id', $request->input('model_id'));
        }
        if ($request->filled('user_id')) {
            $logs->where('user_id', $request->input('user_id'));
        }
        if ($request->filled('action')) {
            $logs->where('action', $request->input('action'));
        }

        $logs = $logs->with(['user'])->orderByDesc('created_at')->paginate(20);

        return view('audit_logs.index', compact('logs'));
    }

    /**
     * Show logs for a specific model instance.
     *
     * @param string $modelType (e.g. "inventory", "App\Models\Inventory")
     * @param int $modelId
     */
    public function logsForResource(Request $request, string $modelType, int $modelId)
    {
        if (!Str::startsWith($modelType, 'App\\Models\\')) {
            $modelType = 'App\\Models\\' . Str::studly($modelType);
        }

        $logs = AuditLog::where('auditable_type', $modelType)
            ->where('auditable_id', $modelId)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('audit_logs.index', [
            'logs' => $logs,
            'resource_type' => $modelType,
            'resource_id' => $modelId,
        ]);
    }

    /**
     * Show details for a single audit log entry.
     */
    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);
        return view('audit_logs.show', compact('log'));
    }
}
