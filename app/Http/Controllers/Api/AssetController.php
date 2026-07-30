<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssetResource;
use App\Models\ActivityLog;
use App\Models\Asset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'creator']);

        if ($request->has('archived') && $request->boolean('archived')) {
            $query->archived();
        } elseif ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['active', 'checked_out']);
        }

        if ($request->has('code')) {
            $asset = $query->where('asset_tag', $request->code)
                ->orWhere('serial', $request->code)
                ->first();

            if (! $asset) {
                return response()->json(['message' => 'Asset not found.'], 404);
            }

            return response()->json(new AssetResource($asset));
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('asset_tag', 'like', "%{$search}%")
                    ->orWhere('serial', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $assets = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 20));

        return AssetResource::collection($assets);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial' => 'required|string|unique:assets',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'condition' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:5120',
        ]);

        $assetTag = DB::transaction(function () {
            $latest = Asset::lockForUpdate()->latest('id')->first();
            $nextNumber = $latest ? (int) substr($latest->asset_tag, -4) + 1 : 1;
            return 'AST-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $photoUrl = $request->file('photo')->store('assets/photos', 'public');
        }

        $asset = Asset::create([
            'name' => $request->name,
            'asset_tag' => $assetTag,
            'serial' => $request->serial,
            'category_id' => $request->category_id,
            'photo_url' => $photoUrl,
            'brand' => $request->brand,
            'model' => $request->model,
            'purchase_date' => $request->purchase_date,
            'purchase_price' => $request->purchase_price,
            'supplier' => $request->supplier,
            'location' => $request->location,
            'condition' => $request->condition,
            'description' => $request->description,
            'created_by' => $request->user()->id,
        ]);

        ActivityLog::create([
            'type' => 'asset_created',
            'asset_id' => $asset->id,
            'user_id' => $request->user()->id,
            'description' => "Asset \"{$asset->name}\" registered ({$asset->asset_tag})",
        ]);

        return (new AssetResource($asset->load(['category', 'creator'])))->response()->setStatusCode(201);
    }

    public function show(Asset $asset): JsonResponse
    {
        return response()->json(new AssetResource($asset->load(['category', 'creator', 'currentCheckout', 'checkouts' => function ($q) {
            $q->latest()->limit(5);
        }])));
    }

    public function update(Request $request, Asset $asset): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'serial' => 'sometimes|string|unique:assets,serial,' . $asset->id,
            'category_id' => 'sometimes|exists:categories,id',
            'status' => 'sometimes|in:active,archived,checked_out,discarded',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'condition' => 'nullable|string|max:255',
            'archived_reason' => 'nullable|string|max:255',
        ]);

        $oldStatus = $asset->status;
        $asset->update($request->except(['photo']));

        if ($request->hasFile('photo')) {
            $asset->photo_url = $request->file('photo')->store('assets/photos', 'public');
            $asset->save();
        }

        if ($request->status === 'archived' && $oldStatus !== 'archived') {
            $asset->update([
                'archived_at' => now(),
                'archived_reason' => $request->archived_reason ?? 'Archived by user',
            ]);

            ActivityLog::create([
                'type' => 'asset_archived',
                'asset_id' => $asset->id,
                'user_id' => $request->user()->id,
                'description' => "Asset \"{$asset->name}\" archived",
                'metadata' => ['reason' => $asset->archived_reason],
            ]);
        }

        if ($request->status === 'discarded' && $oldStatus !== 'discarded') {
            $asset->update([
                'discarded_at' => now(),
                'discarded_reason' => $request->discarded_reason ?? 'Discarded by user',
            ]);

            ActivityLog::create([
                'type' => 'asset_discarded',
                'asset_id' => $asset->id,
                'user_id' => $request->user()->id,
                'description' => "Asset \"{$asset->name}\" discarded",
                'metadata' => ['reason' => $asset->discarded_reason],
            ]);
        }

        if ($request->status === 'active' && $oldStatus === 'archived') {
            $asset->update([
                'archived_at' => null,
                'archived_reason' => null,
            ]);

            ActivityLog::create([
                'type' => 'asset_restored',
                'asset_id' => $asset->id,
                'user_id' => $request->user()->id,
                'description' => "Asset \"{$asset->name}\" restored from archive",
            ]);
        }

        return response()->json(new AssetResource($asset->load(['category', 'creator'])));
    }

    public function destroy(Asset $asset, Request $request): JsonResponse
    {
        $name = $asset->name;
        $tag = $asset->asset_tag;

        $asset->delete();

        ActivityLog::create([
            'type' => 'asset_deleted',
            'asset_id' => null,
            'user_id' => $request->user()->id,
            'description' => "Asset \"{$name}\" ({$tag}) permanently deleted",
        ]);

        return response()->json(['message' => 'Asset deleted successfully.']);
    }

    public function restore(Asset $asset, Request $request): JsonResponse
    {
        if ($asset->status !== 'archived') {
            return response()->json(['message' => 'Asset is not archived.'], 400);
        }

        $asset->update([
            'status' => 'active',
            'archived_at' => null,
            'archived_reason' => null,
        ]);

        ActivityLog::create([
            'type' => 'asset_restored',
            'asset_id' => $asset->id,
            'user_id' => $request->user()->id,
            'description' => "Asset \"{$asset->name}\" restored from archive",
        ]);

        return response()->json($asset->load(['category', 'creator']));
    }
}
