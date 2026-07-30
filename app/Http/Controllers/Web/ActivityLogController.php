<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user', 'asset');

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->search) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        $activities = $query->latest()->paginate(20);
        return view('activity.index', compact('activities'));
    }
}
