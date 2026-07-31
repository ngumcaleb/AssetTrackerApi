<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\CheckOut;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(
        protected NotificationService $notifications
    ) {}

    public function checkout(Asset $asset, User $user, array $data): CheckOut
    {
        if ($asset->status === 'checked_out') {
            throw ValidationException::withMessages([
                'asset_id' => ['Asset is already checked out.'],
            ]);
        }

        if (in_array($asset->status, ['archived', 'discarded'], true)) {
            throw ValidationException::withMessages([
                'asset_id' => ['Cannot check out an archived or discarded asset.'],
            ]);
        }

        $checkout = DB::transaction(function () use ($asset, $user, $data) {
            $checkout = CheckOut::create([
                'asset_id' => $asset->id,
                'user_id' => $user->id,
                'assignee_name' => $data['assignee_name'],
                'department' => $data['department'] ?? null,
                'purpose' => $data['purpose'] ?? null,
                'destination' => $data['destination'] ?? null,
                'expected_return' => $data['expected_return'] ?? null,
                'notes' => $data['notes'] ?? null,
                'checked_out_at' => now(),
            ]);

            $asset->update(['status' => 'checked_out']);

            ActivityLog::create([
                'type' => 'checkout',
                'asset_id' => $asset->id,
                'user_id' => $user->id,
                'description' => "Asset \"{$asset->name}\" checked out to {$data['assignee_name']}",
                'metadata' => [
                    'assignee' => $data['assignee_name'],
                    'department' => $data['department'] ?? null,
                    'destination' => $data['destination'] ?? null,
                    'expected_return' => $data['expected_return'] ?? null,
                ],
            ]);

            return $checkout;
        });

        $this->notifications->notify(
            $user,
            'check_out',
            'Asset checked out',
            "{$asset->name} ({$asset->asset_tag}) checked out to {$data['assignee_name']}.",
            ['asset_id' => $asset->id, 'checkout_id' => $checkout->id]
        );

        return $checkout->load(['asset', 'user']);
    }

    public function returnAsset(CheckOut $checkout, User $user, ?string $returnNotes = null): CheckOut
    {
        if ($checkout->returned_at) {
            throw ValidationException::withMessages([
                'checkout' => ['Asset has already been returned.'],
            ]);
        }

        DB::transaction(function () use ($checkout, $user, $returnNotes) {
            $checkout->update([
                'returned_at' => now(),
                'return_notes' => $returnNotes,
            ]);

            $checkout->asset->update(['status' => 'active']);

            ActivityLog::create([
                'type' => 'return',
                'asset_id' => $checkout->asset_id,
                'user_id' => $user->id,
                'description' => "Asset \"{$checkout->asset->name}\" returned by {$checkout->assignee_name}",
                'metadata' => [
                    'return_notes' => $returnNotes,
                ],
            ]);
        });

        $checkout->refresh()->load(['asset', 'user']);

        $this->notifications->notify(
            $user,
            'check_in',
            'Asset returned',
            "{$checkout->asset->name} ({$checkout->asset->asset_tag}) was returned.",
            ['asset_id' => $checkout->asset_id, 'checkout_id' => $checkout->id]
        );

        return $checkout;
    }
}
