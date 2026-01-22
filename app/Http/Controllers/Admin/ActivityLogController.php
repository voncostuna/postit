<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->get('q', ''));
        $action   = trim((string) $request->get('action', ''));
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        $logsQuery = ActivityLog::query()
            ->with('user')
            ->orderByDesc('created_at');

        // Search
        if ($q !== '') {
            $logsQuery->where(function ($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhere('action', 'like', "%{$q}%")
                    ->orWhere('ip_address', 'like', "%{$q}%")
                    ->orWhere('model_type', 'like', "%{$q}%");
            });
        }

        // Action filter
        if ($action !== '') {
            $logsQuery->where('action', $action);
        }

        // Date filters
        if (!empty($dateFrom)) {
            $logsQuery->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $logsQuery->whereDate('created_at', '<=', $dateTo);
        }

        // Dropdown options for "action"
        $actionOptions = ActivityLog::query()
            ->select('action')
            ->whereNotNull('action')
            ->where('action', '!=', '')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $logs = $logsQuery->paginate(15)->withQueryString();

        return view('admin.activity-logs.index', compact('logs', 'actionOptions'));
    }
}
