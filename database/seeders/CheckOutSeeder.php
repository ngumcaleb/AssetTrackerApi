<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\CheckOut;
use App\Models\User;
use Illuminate\Database\Seeder;

class CheckOutSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $admin = User::first();

        $activeAssets = Asset::active()->get();
        $checkedOutAsset = Asset::where('serial', 'KMT-PC200-07')->first();

        if ($checkedOutAsset) {
            CheckOut::create([
                'asset_id' => $checkedOutAsset->id,
                'user_id' => $admin->id,
                'assignee_name' => 'John Doe',
                'department' => 'Field Services',
                'purpose' => 'Site Deployment',
                'destination' => 'Project Site Alpha, Building 7',
                'expected_return' => now()->addDays(14),
                'notes' => 'Required for excavation phase of construction project.',
                'checked_out_at' => now()->subDays(5),
            ]);
        }

        if ($activeAssets->count() >= 2) {
            CheckOut::create([
                'asset_id' => $activeAssets->first()->id,
                'user_id' => $admin->id,
                'assignee_name' => 'Sarah Jenkins',
                'department' => 'Logistics',
                'purpose' => 'Site Inspection',
                'destination' => 'Warehouse B Extension',
                'expected_return' => now()->addDays(3),
                'notes' => 'Inspection of new storage area.',
                'checked_out_at' => now()->subDays(10),
                'returned_at' => now()->subDays(7),
                'return_notes' => 'Inspection complete, no issues found.',
            ]);

            CheckOut::create([
                'asset_id' => $activeAssets->skip(1)->first()->id,
                'user_id' => $admin->id,
                'assignee_name' => 'Marcus Chen',
                'department' => 'Engineering',
                'purpose' => 'Repair',
                'destination' => 'Workshop A',
                'expected_return' => now()->addDays(7),
                'notes' => 'Scheduled maintenance check.',
                'checked_out_at' => now()->subDays(3),
                'returned_at' => now()->subDays(1),
                'return_notes' => 'Calibration complete, fully operational.',
            ]);

            CheckOut::create([
                'asset_id' => $activeAssets->skip(2)->first()->id,
                'user_id' => $admin->id,
                'assignee_name' => 'Dave Rodriguez',
                'department' => 'Maintenance',
                'purpose' => 'Training',
                'destination' => 'Training Room B',
                'expected_return' => now()->addDays(1),
                'notes' => 'New employee onboarding training.',
                'checked_out_at' => now()->subDays(1),
            ]);
        }
    }
}
