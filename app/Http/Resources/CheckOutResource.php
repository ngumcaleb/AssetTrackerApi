<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckOutResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assignee_name' => $this->assignee_name,
            'department' => $this->department,
            'purpose' => $this->purpose,
            'destination' => $this->destination,
            'expected_return' => $this->expected_return
                ? ($this->expected_return instanceof \DateTimeInterface ? $this->expected_return->toDateString() : (string) $this->expected_return)
                : null,
            'notes' => $this->notes,
            'checked_out_at' => $this->checked_out_at
                ? ($this->checked_out_at instanceof \DateTimeInterface ? $this->checked_out_at->toIso8601String() : (string) $this->checked_out_at)
                : now()->toIso8601String(),
            'returned_at' => $this->returned_at
                ? ($this->returned_at instanceof \DateTimeInterface ? $this->returned_at->toIso8601String() : (string) $this->returned_at)
                : null,
            'return_notes' => $this->return_notes,
            'asset' => new AssetResource($this->whenLoaded('asset')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
