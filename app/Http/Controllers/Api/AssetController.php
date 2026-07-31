<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ArchiveAssetRequest;
use App\Http\Requests\Api\StoreAssetRequest;
use App\Http\Requests\Api\UpdateAssetRequest;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use App\Services\AssetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    use RespondsWithJson;

    public function __construct(
        protected AssetService $assets
    ) {}

    public function index(Request $request)
    {
        if ($request->filled('code')) {
            return $this->lookup($request);
        }

        $query = Asset::with(['category', 'creator', 'currentCheckout']);

        if ($request->boolean('archived')) {
            $query->archived();
        } elseif ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        } else {
            $query->whereIn('status', ['active', 'checked_out']);
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('asset_tag', 'like', "%{$search}%")
                    ->orWhere('serial', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%'.$request->string('location').'%');
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->string('condition'));
        }

        $assets = $query->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 20));

        return $this->respondCollection(AssetResource::collection($assets));
    }

    public function lookup(Request $request): JsonResponse
    {
        $code = $request->string('code')->trim()->toString();

        if ($code === '') {
            return $this->error('A code (asset tag or serial) is required.', 422);
        }

        $asset = $this->assets->findByCode($code);

        if (! $asset) {
            return $this->error('Asset not found.', 404);
        }

        return $this->respond(new AssetResource($asset));
    }

    public function store(StoreAssetRequest $request): JsonResponse
    {
        $asset = $this->assets->create(
            $request->safe()->except(['photo']),
            $request->user(),
            $request->file('photo')
        );

        return $this->respond(new AssetResource($asset), 201);
    }

    public function show(Asset $asset): JsonResponse
    {
        $asset->load([
            'category',
            'creator',
            'currentCheckout.user',
            'checkouts' => fn ($q) => $q->latest()->limit(5),
            'activityLogs' => fn ($q) => $q->latest()->limit(10),
        ]);

        return $this->respond(new AssetResource($asset));
    }

    public function update(UpdateAssetRequest $request, Asset $asset): JsonResponse
    {
        $asset = $this->assets->update(
            $asset,
            $request->safe()->except(['photo']),
            $request->user(),
            $request->file('photo')
        );

        return $this->respond(new AssetResource($asset));
    }

    public function destroy(Asset $asset, Request $request): JsonResponse
    {
        $this->assets->delete($asset, $request->user());

        return $this->message('Asset deleted successfully.');
    }

    public function restore(Asset $asset, Request $request): JsonResponse
    {
        $asset = $this->assets->restore($asset, $request->user());

        return $this->respond(new AssetResource($asset));
    }

    public function archive(ArchiveAssetRequest $request, Asset $asset): JsonResponse
    {
        $asset = $this->assets->archive($asset, $request->user(), $request->string('reason')->toString());

        return $this->respond(new AssetResource($asset));
    }

    public function discard(ArchiveAssetRequest $request, Asset $asset): JsonResponse
    {
        $asset = $this->assets->discard($asset, $request->user(), $request->string('reason')->toString());

        return $this->respond(new AssetResource($asset));
    }
}
