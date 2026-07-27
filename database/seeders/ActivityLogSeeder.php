<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $assets = Asset::all();
        $admin = User::first();

        $activities = [
            [
                'type' => 'asset_created',
                'description' => 'Asset "Caterpillar Forklift X-200" registered (AST-2024-0001)',
                'days_ago' => 45,
            ],
            [
                'type' => 'asset_created',
                'description' => 'Asset "Dell PowerEdge R740" registered (AST-2024-0007)',
                'days_ago' => 30,
            ],
            [
                'type' => 'checkout',
                'description' => 'Asset "Komatsu Excavator PC200" checked out to John Doe',
                'days_ago' => 5,
                'metadata' => ['assignee' => 'John Doe', 'department' => 'Field Services', 'destination' => 'Project Site Alpha'],
            ],
            [
                'type' => 'checkout',
                'description' => 'Asset "Caterpillar Forklift X-200" checked out to Sarah Jenkins',
                'days_ago' => 10,
            ],
            [
                'type' => 'return',
                'description' => 'Asset "Caterpillar Forklift X-200" returned by Sarah Jenkins',
                'days_ago' => 7,
                'metadata' => ['return_notes' => 'Inspection complete, no issues found.'],
            ],
            [
                'type' => 'checkout',
                'description' => 'Asset "Precision Laser Level" checked out to Marcus Chen',
                'days_ago' => 3,
            ],
            [
                'type' => 'return',
                'description' => 'Asset "Precision Laser Level" returned by Marcus Chen',
                'days_ago' => 1,
                'metadata' => ['return_notes' => 'Calibration complete, fully operational.'],
            ],
            [
                'type' => 'maintenance',
                'description' => 'Maintenance performed on "HVAC Unit B-Tier"',
                'days_ago' => 2,
                'metadata' => ['technician' => 'Dave Rodriguez', 'notes' => 'Preventative maintenance completed.'],
            ],
            [
                'type' => 'asset_created',
                'description' => 'Asset "New Pallet Jack P-5" registered (AST-2024-0010)',
                'days_ago' => 1,
            ],
            [
                'type' => 'verification_mismatch',
                'description' => 'QR verification mismatch detected for asset "Handheld Scanner V1"',
                'days_ago' => 8,
                'metadata' => ['scanned_code' => 'SCAN-999', 'expected_asset' => 'Handheld Scanner V1', 'matched_asset' => null],
            ],
            [
                'type' => 'asset_archived',
                'description' => 'Asset "Old Forklift Model T" archived',
                'days_ago' => 90,
                'metadata' => ['reason' => 'Irreparable Damage'],
            ],
            [
                'type' => 'asset_restored',
                'description' => 'Asset "Digital Scanner 09" restored from archive',
                'days_ago' => 20,
            ],
            [
                'type' => 'checkout',
                'description' => 'Asset "Industrial Compressor XL" checked out to Dave Rodriguez',
                'days_ago' => 15,
            ],
            [
                'type' => 'return',
                'description' => 'Asset "Industrial Compressor XL" returned by Dave Rodriguez',
                'days_ago' => 12,
                'metadata' => ['return_notes' => 'Routine inspection completed.'],
            ],
        ];

        foreach ($activities as $activity) {
            $asset = $assets->random();
            $user = $users->random();

            ActivityLog::create([
                'type' => $activity['type'],
                'asset_id' => $asset->id,
                'user_id' => $user->id,
                'description' => $activity['description'],
                'metadata' => $activity['metadata'] ?? null,
                'created_at' => now()->subDays($activity['days_ago'])->subHours(rand(0, 12)),
            ]);
        }
    }
}
