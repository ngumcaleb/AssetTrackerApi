<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreActivityLogRequest;
use App\Http\Resources\ActivityLogResource;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    use RespondsWithJson;

    public function index(Request $request)
    {
        $query = ActivityLog::with(['asset', 'user']);

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->integer('asset_id'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('asset', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('asset_tag', 'like', "%{$search}%");
                    });
            });
        }

        $logs = $query->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 30));

        return $this->respondCollection(ActivityLogResource::collection($logs));
    }

    public function store(StoreActivityLogRequest $request): JsonResponse
    {
        $log = ActivityLog::create([
            'type' => $request->type,
            'asset_id' => $request->asset_id,
            'user_id' => $request->user()->id,
            'description' => $request->description,
            'metadata' => $request->metadata,
        ]);

        return $this->respond(new ActivityLogResource($log->load(['asset', 'user'])), 201);
    }
}
