<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AssetService
{
    public function __construct(
        protected NotificationService $notifications
    ) {}

    public function generateAssetTag(): string
    {
        return DB::transaction(function () {
            $latest = Asset::lockForUpdate()->latest('id')->first();
            $nextNumber = $latest ? ((int) substr((string) $latest->asset_tag, -4)) + 1 : 1;

            return 'AST-'.date('Y').'-'.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    public function storePhoto(?UploadedFile $photo): ?string
    {
        if (! $photo) {
            return null;
        }

        return $photo->store('assets/photos', 'public');
    }

    public function replacePhoto(Asset $asset, UploadedFile $photo): string
    {
        $path = $this->storePhoto($photo);

        if ($asset->photo_url && ! str_starts_with($asset->photo_url, 'http')) {
            Storage::disk('public')->delete($asset->photo_url);
        }

        return $path;
    }

    public function create(array $data, User $user, ?UploadedFile $photo = null): Asset
    {
        $asset = Asset::create([
            ...$data,
            'asset_tag' => $data['asset_tag'] ?? $this->generateAssetTag(),
            'photo_url' => $this->storePhoto($photo),
            'status' => 'active',
            'created_by' => $user->id,
        ]);

        ActivityLog::create([
            'type' => 'asset_created',
            'asset_id' => $asset->id,
            'user_id' => $user->id,
            'description' => "Asset \"{$asset->name}\" registered ({$asset->asset_tag})",
        ]);

        $this->notifications->notify(
            $user,
            'system',
            'Asset registered',
            "Asset \"{$asset->name}\" ({$asset->asset_tag}) was registered.",
            ['asset_id' => $asset->id]
        );

        return $asset->load(['category', 'creator']);
    }

    public function update(Asset $asset, array $data, User $user, ?UploadedFile $photo = null): Asset
    {
        $oldStatus = $asset->status;

        if ($photo) {
            $data['photo_url'] = $this->replacePhoto($asset, $photo);
        }

        unset($data['photo']);

        if (isset($data['status']) && $data['status'] !== $oldStatus) {
            $this->assertStatusTransitionAllowed($asset, $data['status']);
        }

        $asset->update($data);
        $asset->refresh();

        if (($data['status'] ?? null) === 'archived' && $oldStatus !== 'archived') {
            $asset->update([
                'archived_at' => now(),
                'archived_reason' => $data['archived_reason'] ?? $asset->archived_reason ?? 'Archived by user',
                'discarded_at' => null,
                'discarded_reason' => null,
            ]);

            ActivityLog::create([
                'type' => 'asset_archived',
                'asset_id' => $asset->id,
                'user_id' => $user->id,
                'description' => "Asset \"{$asset->name}\" archived",
                'metadata' => ['reason' => $asset->archived_reason],
            ]);

            $this->notifications->notify(
                $user,
                'system',
                'Asset archived',
                "Asset \"{$asset->name}\" was archived.",
                ['asset_id' => $asset->id]
            );
        }

        if (($data['status'] ?? null) === 'discarded' && $oldStatus !== 'discarded') {
            $asset->update([
                'discarded_at' => now(),
                'discarded_reason' => $data['discarded_reason'] ?? $asset->discarded_reason ?? 'Discarded by user',
            ]);

            ActivityLog::create([
                'type' => 'asset_discarded',
                'asset_id' => $asset->id,
                'user_id' => $user->id,
                'description' => "Asset \"{$asset->name}\" discarded",
                'metadata' => ['reason' => $asset->discarded_reason],
            ]);
        }

        if (($data['status'] ?? null) === 'active' && in_array($oldStatus, ['archived', 'discarded'], true)) {
            $asset->update([
                'archived_at' => null,
                'archived_reason' => null,
                'discarded_at' => null,
                'discarded_reason' => null,
            ]);

            ActivityLog::create([
                'type' => 'asset_restored',
                'asset_id' => $asset->id,
                'user_id' => $user->id,
                'description' => "Asset \"{$asset->name}\" restored",
            ]);
        }

        return $asset->load(['category', 'creator']);
    }

    public function archive(Asset $asset, User $user, string $reason): Asset
    {
        $this->assertNotCheckedOut($asset);

        return $this->update($asset, [
            'status' => 'archived',
            'archived_reason' => $reason,
        ], $user);
    }

    public function discard(Asset $asset, User $user, string $reason): Asset
    {
        $this->assertNotCheckedOut($asset);

        return $this->update($asset, [
            'status' => 'discarded',
            'discarded_reason' => $reason,
        ], $user);
    }

    public function restore(Asset $asset, User $user): Asset
    {
        if (! in_array($asset->status, ['archived', 'discarded'], true)) {
            throw ValidationException::withMessages([
                'status' => ['Asset is not archived or discarded.'],
            ]);
        }

        return $this->update($asset, ['status' => 'active'], $user);
    }

    public function delete(Asset $asset, User $user): void
    {
        $name = $asset->name;
        $tag = $asset->asset_tag;

        DB::transaction(function () use ($asset) {
            $asset->checkouts()->delete();
            $asset->activityLogs()->update(['asset_id' => null]);
            if ($asset->photo_url && ! str_starts_with($asset->photo_url, 'http')) {
                Storage::disk('public')->delete($asset->photo_url);
            }
            $asset->delete();
        });

        ActivityLog::create([
            'type' => 'asset_deleted',
            'asset_id' => null,
            'user_id' => $user->id,
            'description' => "Asset \"{$name}\" ({$tag}) permanently deleted",
        ]);
    }

    public function findByCode(string $code): ?Asset
    {
        $raw = trim($code);
        if ($raw === '') {
            return null;
        }

        // Web-printed QR codes encode the asset show URL, e.g.
        // https://assettracker.nolivers.com/assets/12
        if (preg_match('#/assets/(\d+)(?:/|$|\?|#)#i', $raw, $matches)) {
            return Asset::with(['category', 'creator', 'currentCheckout'])
                ->find((int) $matches[1]);
        }

        // Deep-link style: assettracker://asset/AST-2026-0001 or .../asset/12
        if (preg_match('#(?:assettracker://|/)(?:asset|assets)/([A-Za-z0-9\-]+)#i', $raw, $matches)) {
            $token = $matches[1];
            if (ctype_digit($token)) {
                return Asset::with(['category', 'creator', 'currentCheckout'])
                    ->find((int) $token);
            }

            return Asset::with(['category', 'creator', 'currentCheckout'])
                ->where(function ($q) use ($token) {
                    $q->where('asset_tag', $token)
                        ->orWhere('serial', $token);
                })
                ->first();
        }

        // Plain numeric ID
        if (ctype_digit($raw)) {
            $byId = Asset::with(['category', 'creator', 'currentCheckout'])->find((int) $raw);
            if ($byId) {
                return $byId;
            }
        }

        // Exact asset tag / serial (case-insensitive for tags)
        return Asset::with(['category', 'creator', 'currentCheckout'])
            ->where(function ($q) use ($raw) {
                $q->whereRaw('LOWER(asset_tag) = ?', [mb_strtolower($raw)])
                    ->orWhereRaw('LOWER(serial) = ?', [mb_strtolower($raw)])
                    ->orWhere('asset_tag', $raw)
                    ->orWhere('serial', $raw);
            })
            ->first();
    }

    protected function assertNotCheckedOut(Asset $asset): void
    {
        if ($asset->status === 'checked_out') {
            throw ValidationException::withMessages([
                'status' => ['Return the asset before changing its status.'],
            ]);
        }
    }

    protected function assertStatusTransitionAllowed(Asset $asset, string $newStatus): void
    {
        if ($newStatus === 'checked_out') {
            throw ValidationException::withMessages([
                'status' => ['Use the checkout endpoint to check out an asset.'],
            ]);
        }

        if (in_array($newStatus, ['archived', 'discarded'], true)) {
            $this->assertNotCheckedOut($asset);
        }
    }
}
