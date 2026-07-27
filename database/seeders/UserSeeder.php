<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@scantrack.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'Management',
            'phone' => '+1-555-0100',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Sarah Jenkins',
            'email' => 'sarah@scantrack.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'department' => 'Logistics',
            'phone' => '+1-555-0101',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Marcus Chen',
            'email' => 'marcus@scantrack.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'department' => 'Engineering',
            'phone' => '+1-555-0102',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Dave Rodriguez',
            'email' => 'dave@scantrack.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'department' => 'Maintenance',
            'phone' => '+1-555-0103',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Mike Chen',
            'email' => 'mike@scantrack.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'department' => 'Field Services',
            'phone' => '+1-555-0104',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'System Admin',
            'email' => 'system@scantrack.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'IT',
            'phone' => '+1-555-0105',
            'is_active' => true,
        ]);
    }
}
