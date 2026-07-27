<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Industrial Equipment', 'slug' => 'industrial-equipment', 'icon' => 'precision_manufacturing', 'description' => 'Heavy machinery and industrial tools for manufacturing and construction.'],
            ['name' => 'IT Infrastructure', 'slug' => 'it-infrastructure', 'icon' => 'laptop_mac', 'description' => 'Servers, networking equipment, and computing devices.'],
            ['name' => 'Fleet Vehicles', 'slug' => 'fleet-vehicles', 'icon' => 'local_shipping', 'description' => 'Company vehicles including trucks, vans, and forklifts.'],
            ['name' => 'Office Furniture', 'slug' => 'office-furniture', 'icon' => 'chair', 'description' => 'Desks, chairs, and office equipment.'],
            ['name' => 'Manufacturing Tools', 'slug' => 'manufacturing-tools', 'icon' => 'build', 'description' => 'Specialized tools for production and assembly lines.'],
            ['name' => 'Network Infrastructure', 'slug' => 'network-infrastructure', 'icon' => 'router', 'description' => 'Routers, switches, and networking hardware.'],
            ['name' => 'Lab Equipment', 'slug' => 'lab-equipment', 'icon' => 'biotech', 'description' => 'Testing and laboratory instruments.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
