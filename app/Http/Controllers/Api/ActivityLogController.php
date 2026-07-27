<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['asset', 'user']);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('asset', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('asset_tag', 'like', "%{$search}%");
                    });
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 30));

        return ActivityLogResource::collection($logs);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'asset_id' => 'nullable|exists:assets,id',
            'description' => 'required|string',
            'metadata' => 'nullable|array',
        ]);

        $log = ActivityLog::create([
            'type' => $request->type,
            'asset_id' => $request->asset_id,
            'user_id' => $request->user()->id,
            'description' => $request->description,
            'metadata' => $request->metadata,
        ]);

        return (new ActivityLogResource($log->load(['asset', 'user'])))->response()->setStatusCode(201);
    }
}
