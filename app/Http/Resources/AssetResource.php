<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'asset_tag' => $this->asset_tag,
            'serial' => $this->serial,
            'status' => $this->status,
            'photo_url' => $this->photo_url ? url('storage/' . $this->photo_url) : null,
            'brand' => $this->brand,
            'model' => $this->model,
            'purchase_date' => $this->purchase_date?->toDateString(),
            'purchase_price' => $this->purchase_price,
            'condition' => $this->condition,
            'supplier' => $this->supplier,
            'location' => $this->location,
            'description' => $this->description,
            'archived_at' => $this->archived_at?->toIso8601String(),
            'archived_reason' => $this->archived_reason,
            'discarded_at' => $this->discarded_at?->toIso8601String(),
            'discarded_reason' => $this->discarded_reason,
            'created_at' => $this->created_at->toIso8601String(),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'current_checkout' => new CheckOutResource($this->whenLoaded('currentCheckout')),
            'checkouts' => CheckOutResource::collection($this->whenLoaded('checkouts')),
            'activity_logs' => ActivityLogResource::collection($this->whenLoaded('activityLogs')),
        ];
    }
}
