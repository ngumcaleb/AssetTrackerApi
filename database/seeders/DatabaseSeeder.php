<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\Category;
use App\Models\CheckOut;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            AssetSeeder::class,
            CheckOutSeeder::class,
            ActivityLogSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
