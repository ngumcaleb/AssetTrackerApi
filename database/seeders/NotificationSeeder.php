<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@scantrack.com')->first();

        if (! $admin) {
            return;
        }

        $notifications = [
            [
                'type' => 'check_in',
                'title' => 'Asset Checked In',
                'description' => 'Dell XPS 15 (AST-2024-0012) has been returned by John Smith.',
                'metadata' => ['asset_tag' => 'AST-2024-0012', 'asset_name' => 'Dell XPS 15'],
            ],
            [
                'type' => 'check_out',
                'title' => 'Asset Checked Out',
                'description' => 'HP LaserJet Pro (AST-2024-0005) has been assigned to Sarah Johnson.',
                'metadata' => ['asset_tag' => 'AST-2024-0005', 'asset_name' => 'HP LaserJet Pro'],
            ],
            [
                'type' => 'maintenance',
                'title' => 'Maintenance Scheduled',
                'description' => 'CNC Machine Unit 3 (AST-2024-0020) has been flagged for maintenance.',
                'metadata' => ['asset_tag' => 'AST-2024-0020', 'asset_name' => 'CNC Machine Unit 3'],
            ],
            [
                'type' => 'system',
                'title' => 'System Update',
                'description' => 'ScanTrack has been updated to version 2.1.0 with new features and improvements.',
                'metadata' => ['version' => '2.1.0'],
            ],
            [
                'type' => 'check_out',
                'title' => 'Overdue Return',
                'description' => 'MacBook Pro 16" (AST-2024-0008) return date has passed. Expected: 2024-01-15.',
                'metadata' => ['asset_tag' => 'AST-2024-0008', 'asset_name' => 'MacBook Pro 16"'],
                'read_at' => now()->subDays(2),
            ],
        ];

        foreach ($notifications as $index => $notification) {
            Notification::create(array_merge($notification, [
                'user_id' => $admin->id,
                'created_at' => now()->subHours(count($notifications) - $index),
            ]));
        }
    }
}
