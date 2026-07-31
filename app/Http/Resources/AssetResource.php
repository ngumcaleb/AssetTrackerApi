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
            'photo_url' => $this->resolvePhotoUrl(),
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

    protected function resolvePhotoUrl(): ?string
    {
        if (! $this->photo_url) {
            return null;
        }

        $path = str_replace('\\', '/', (string) $this->photo_url);

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Normalize DB values like "assets/photos/x.jpg" or "storage/assets/photos/x.jpg"
        $path = ltrim($path, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        // Prefer the current request host so mobile clients never get a stale APP_URL
        // (e.g. localhost) from shared-hosting env files.
        $base = rtrim(request()->getSchemeAndHttpHost().request()->getBasePath(), '/');

        return $base.'/storage/'.$path;
    }
}
