<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\CheckOut;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalAssets = Asset::count();
        $activeAssets = Asset::where('status', 'active')->count();
        $checkedOut = Asset::where('status', 'checked_out')->count();
        $archived = Asset::where('status', 'archived')->count();

        $recentAssets = Asset::latest()->take(5)->get();
        $activeCheckouts = CheckOut::whereNull('returned_at')->with('asset', 'user')->latest()->take(5)->get();
        $recentActivity = ActivityLog::with('user', 'asset')->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalAssets', 'activeAssets', 'checkedOut', 'archived',
            'recentAssets', 'activeCheckouts', 'recentActivity'
        ));
    }
}
