<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CheckOutResource;
use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\CheckOut;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckOutController extends Controller
{
    public function index(Request $request)
    {
        $query = CheckOut::with(['asset', 'user']);

        if ($request->has('active') && $request->boolean('active')) {
            $query->whereNull('returned_at');
        }

        if ($request->has('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        $checkouts = $query->orderBy('checked_out_at', 'desc')->paginate($request->get('per_page', 20));

        return CheckOutResource::collection($checkouts);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'assignee_name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'expected_return' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $asset = Asset::findOrFail($request->asset_id);

        if ($asset->status === 'checked_out') {
            return response()->json([
                'message' => 'Asset is already checked out.',
            ], 400);
        }

        $checkout = DB::transaction(function () use ($request, $asset) {
            $checkout = CheckOut::create([
                'asset_id' => $asset->id,
                'user_id' => $request->user()->id,
                'assignee_name' => $request->assignee_name,
                'department' => $request->department,
                'purpose' => $request->purpose,
                'destination' => $request->destination,
                'expected_return' => $request->expected_return,
                'notes' => $request->notes,
                'checked_out_at' => now(),
            ]);

            $asset->update(['status' => 'checked_out']);

            ActivityLog::create([
                'type' => 'checkout',
                'asset_id' => $asset->id,
                'user_id' => $request->user()->id,
                'description' => "Asset \"{$asset->name}\" checked out to {$request->assignee_name}",
                'metadata' => [
                    'assignee' => $request->assignee_name,
                    'department' => $request->department,
                    'destination' => $request->destination,
                    'expected_return' => $request->expected_return,
                ],
            ]);

            return $checkout;
        });

        return (new CheckOutResource($checkout->load('asset')))->response()->setStatusCode(201);
    }

    public function returnAsset(CheckOut $checkout, Request $request): JsonResponse
    {
        if ($checkout->returned_at) {
            return response()->json([
                'message' => 'Asset has already been returned.',
            ], 400);
        }

        $request->validate([
            'return_notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($checkout, $request) {
            $checkout->update([
                'returned_at' => now(),
                'return_notes' => $request->return_notes,
            ]);

            $checkout->asset->update(['status' => 'active']);

            ActivityLog::create([
                'type' => 'return',
                'asset_id' => $checkout->asset_id,
                'user_id' => $request->user()->id,
                'description' => "Asset \"{$checkout->asset->name}\" returned by {$checkout->assignee_name}",
                'metadata' => [
                    'return_notes' => $request->return_notes,
                ],
            ]);
        });

        return response()->json(new CheckOutResource($checkout->load('asset')));
    }

    public function show(CheckOut $checkout): JsonResponse
    {
        return response()->json(new CheckOutResource($checkout->load(['asset', 'user'])));
    }
}
