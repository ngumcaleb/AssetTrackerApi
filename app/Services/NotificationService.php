<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    public function notify(User $user, string $type, string $title, string $description, ?array $metadata = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    /**
     * @param  Collection<int, User>|iterable<User>  $users
     */
    public function notifyMany(iterable $users, string $type, string $title, string $description, ?array $metadata = null): void
    {
        foreach ($users as $user) {
            $this->notify($user, $type, $title, $description, $metadata);
        }
    }

    public function notifyAdmins(string $type, string $title, string $description, ?array $metadata = null, ?int $exceptUserId = null): void
    {
        $query = User::query()
            ->where('is_active', true)
            ->where('role', 'admin');

        if ($exceptUserId) {
            $query->where('id', '!=', $exceptUserId);
        }

        $this->notifyMany($query->get(), $type, $title, $description, $metadata);
    }
}
