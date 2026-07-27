<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\CheckOut;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(): JsonResponse
    {
        $total = Asset::count();
        $active = Asset::active()->count();
        $archived = Asset::archived()->count();
        $checkedOut = Asset::checkedOut()->count();

        $damaged = Asset::where('status', 'archived')
            ->where('archived_reason', 'like', '%damage%')
            ->count();

        $expired = CheckOut::whereNull('returned_at')
            ->where('expected_return', '<', now())
            ->count();

        $recentCheckouts = CheckOut::with('asset')
            ->whereNull('returned_at')
            ->orderBy('checked_out_at', 'desc')
            ->limit(5)
            ->get();

        $recentAssets = Asset::with('category')
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'total' => $total,
            'active' => $active,
            'damaged' => $damaged,
            'expired' => $expired,
            'checked_out' => $checkedOut,
            'recent_checkouts' => $recentCheckouts,
            'recent_assets' => $recentAssets,
        ]);
    }
}
