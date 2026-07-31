<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'description' => $this->description,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at
                ? ($this->created_at instanceof \DateTimeInterface ? $this->created_at->toIso8601String() : (string) $this->created_at)
                : now()->toIso8601String(),
            'asset' => new AssetResource($this->whenLoaded('asset')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
