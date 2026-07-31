<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReturnCheckoutRequest;
use App\Http\Requests\Api\StoreCheckoutRequest;
use App\Http\Resources\CheckOutResource;
use App\Models\Asset;
use App\Models\CheckOut;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckOutController extends Controller
{
    use RespondsWithJson;

    public function __construct(
        protected CheckoutService $checkouts
    ) {}

    public function index(Request $request)
    {
        $query = CheckOut::with(['asset.category', 'user']);

        if ($request->filled('status')) {
            if ($request->string('status')->toString() === 'active') {
                $query->whereNull('returned_at');
            } elseif ($request->string('status')->toString() === 'returned') {
                $query->whereNotNull('returned_at');
            }
        } elseif ($request->boolean('active')) {
            $query->whereNull('returned_at');
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('assignee_name', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhereHas('asset', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('asset_tag', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->integer('asset_id'));
        }

        $checkouts = $query->orderByDesc('checked_out_at')
            ->paginate($request->integer('per_page', 20));

        return $this->respondCollection(CheckOutResource::collection($checkouts));
    }

    public function store(StoreCheckoutRequest $request): JsonResponse
    {
        $asset = Asset::findOrFail($request->integer('asset_id'));

        $checkout = $this->checkouts->checkout(
            $asset,
            $request->user(),
            $request->validated()
        );

        return $this->respond(new CheckOutResource($checkout), 201);
    }

    public function returnAsset(ReturnCheckoutRequest $request, CheckOut $checkout): JsonResponse
    {
        $checkout = $this->checkouts->returnAsset(
            $checkout,
            $request->user(),
            $request->input('return_notes')
        );

        return $this->respond(new CheckOutResource($checkout));
    }

    public function show(CheckOut $checkout): JsonResponse
    {
        return $this->respond(new CheckOutResource($checkout->load(['asset.category', 'user'])));
    }
}
