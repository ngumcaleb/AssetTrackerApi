<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use App\Http\Resources\AssetResource;
use App\Http\Resources\CheckOutResource;
use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\CheckOut;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use RespondsWithJson;

    public function summary(): JsonResponse
    {
        $total = Asset::count();
        $active = Asset::active()->count();
        $archived = Asset::archived()->count();
        $discarded = Asset::discarded()->count();
        $checkedOut = Asset::checkedOut()->count();

        $damaged = Asset::where('status', 'archived')
            ->where(function ($q) {
                $q->where('archived_reason', 'like', '%damage%')
                    ->orWhere('condition', 'like', '%poor%')
                    ->orWhere('condition', 'like', '%damage%');
            })
            ->count();

        $expired = CheckOut::whereNull('returned_at')
            ->whereNotNull('expected_return')
            ->whereDate('expected_return', '<', now()->toDateString())
            ->count();

        $recentCheckouts = CheckOut::with('asset')
            ->whereNull('returned_at')
            ->orderByDesc('checked_out_at')
            ->limit(5)
            ->get();

        $recentAssets = Asset::with('category')
            ->latest()
            ->limit(5)
            ->get();

        $recentActivity = ActivityLog::with(['user', 'asset'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return response()->json([
            'total' => $total,
            'active' => $active,
            'archived' => $archived,
            'discarded' => $discarded,
            'damaged' => $damaged,
            'expired' => $expired,
            'checked_out' => $checkedOut,
            'recent_checkouts' => CheckOutResource::collection($recentCheckouts)->resolve(),
            'recent_assets' => AssetResource::collection($recentAssets)->resolve(),
            'recent_activity' => ActivityLogResource::collection($recentActivity)->resolve(),
        ]);
    }
}
