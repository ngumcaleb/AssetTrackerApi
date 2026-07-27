<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $users = User::all();
        $admin = User::first();

        $assets = [
            ['name' => 'Caterpillar Forklift X-200', 'serial' => 'CAT-X200-001', 'brand' => 'Caterpillar', 'model' => 'X-200 Series', 'category' => 'Fleet Vehicles', 'status' => 'active', 'location' => 'Warehouse A, Bay 1', 'supplier' => 'Caterpillar Inc.', 'purchase_price' => 45000.00, 'description' => 'Heavy-duty forklift for warehouse operations.'],
            ['name' => 'Old Forklift Model T', 'serial' => '4920-XJ-99', 'brand' => 'Toyota', 'model' => 'Model T', 'category' => 'Fleet Vehicles', 'status' => 'archived', 'location' => 'Warehouse B', 'supplier' => 'Toyota Material Handling', 'purchase_price' => 32000.00, 'archived_reason' => 'Irreparable Damage', 'description' => 'Decommissioned due to hydraulic failure.'],
            ['name' => 'Broken Laser Cutter', 'serial' => 'LZR-882-B', 'brand' => 'Trumpf', 'model' => 'LC-882', 'category' => 'Manufacturing Tools', 'status' => 'archived', 'location' => 'Manufacturing Floor', 'supplier' => 'Trumpf GmbH', 'purchase_price' => 78000.00, 'archived_reason' => 'Decommissioned', 'description' => 'End of service life, irreparable laser assembly.'],
            ['name' => 'Handheld Scanner V1', 'serial' => 'SCAN-001', 'brand' => 'Honeywell', 'model' => 'Voyager 1200g', 'category' => 'IT Infrastructure', 'status' => 'archived', 'location' => 'Shipping Dept', 'supplier' => 'Honeywell International', 'purchase_price' => 350.00, 'archived_reason' => 'End of Life Cycle', 'description' => 'Legacy barcode scanner replaced by V2.'],
            ['name' => 'Precision Laser Level', 'serial' => 'ST-9942-B', 'brand' => 'Bosch', 'model' => 'GLL 3-80 CG', 'category' => 'Manufacturing Tools', 'status' => 'active', 'location' => 'Workshop C', 'supplier' => 'Bosch Power Tools', 'purchase_price' => 890.00, 'description' => '360-degree green beam laser level for precision alignment.'],
            ['name' => 'Industrial Compressor XL', 'serial' => 'CMPR-77492', 'brand' => 'Atlas Copco', 'model' => 'GA 37+', 'category' => 'Industrial Equipment', 'status' => 'active', 'location' => 'Plant Room 2', 'supplier' => 'Atlas Copco', 'purchase_price' => 25000.00, 'description' => 'Rotary screw air compressor for pneumatic tools.'],
            ['name' => 'Dell PowerEdge R740', 'serial' => 'SRV-2024-001', 'brand' => 'Dell', 'model' => 'PowerEdge R740', 'category' => 'IT Infrastructure', 'status' => 'active', 'location' => 'Server Room', 'supplier' => 'Dell Technologies', 'purchase_price' => 12500.00, 'description' => 'Rack server for enterprise application hosting.'],
            ['name' => 'Cisco Catalyst 9300', 'serial' => 'CISCO-9300-12', 'brand' => 'Cisco', 'model' => 'Catalyst 9300', 'category' => 'Network Infrastructure', 'status' => 'active', 'location' => 'Server Room', 'supplier' => 'Cisco Systems', 'purchase_price' => 8200.00, 'description' => '48-port PoE+ network switch.'],
            ['name' => 'HVAC Unit B-Tier', 'serial' => 'HVAC-BT-442', 'brand' => 'Carrier', 'model' => '42CE', 'category' => 'Industrial Equipment', 'status' => 'active', 'location' => 'Building B', 'supplier' => 'Carrier Corporation', 'purchase_price' => 15000.00, 'description' => 'Commercial HVAC unit for climate control.'],
            ['name' => 'New Pallet Jack P-5', 'serial' => 'PJ-0005', 'brand' => 'Hyundai', 'model' => 'PBD-50', 'category' => 'Industrial Equipment', 'status' => 'active', 'location' => 'Warehouse A', 'supplier' => 'Hyundai Material Handling', 'purchase_price' => 2800.00, 'description' => 'Electric pallet jack for heavy loads up to 500kg.'],
            ['name' => 'Digital Scanner 09', 'serial' => 'DSC-009', 'brand' => 'Zebra', 'model' => 'DS3678', 'category' => 'IT Infrastructure', 'status' => 'active', 'location' => 'Receiving Dock', 'supplier' => 'Zebra Technologies', 'purchase_price' => 650.00, 'description' => 'Rugged wireless barcode scanner.'],
            ['name' => 'High-Precision Laser Welder', 'serial' => '9920-ABC-X', 'brand' => 'IPG Photonics', 'model' => 'YLS-2000', 'category' => 'Manufacturing Tools', 'status' => 'active', 'location' => 'Manufacturing Floor (Bay 4)', 'supplier' => 'IPG Photonics', 'purchase_price' => 95000.00, 'description' => 'Fiber laser welding system for precision joining.'],
            ['name' => 'Komatsu Excavator PC200', 'serial' => 'KMT-PC200-07', 'brand' => 'Komatsu', 'model' => 'PC200-8M0', 'category' => 'Fleet Vehicles', 'status' => 'checked_out', 'location' => 'Site Alpha', 'supplier' => 'Komatsu Ltd.', 'purchase_price' => 185000.00, 'description' => '20-ton class hydraulic excavator.'],
            ['name' => 'Agilent Spectrophotometer', 'serial' => 'SPEC-AG-550', 'brand' => 'Agilent', 'model' => 'Cary 5000', 'category' => 'Lab Equipment', 'status' => 'active', 'location' => 'Lab 1', 'supplier' => 'Agilent Technologies', 'purchase_price' => 35000.00, 'description' => 'UV-Vis-NIR spectrophotometer for material analysis.'],
            ['name' => 'Ergonomic Office Chair Set', 'serial' => 'OFC-CHAIR-24', 'brand' => 'Herman Miller', 'model' => 'Aeron', 'category' => 'Office Furniture', 'status' => 'active', 'location' => 'Office Wing A', 'supplier' => 'Herman Miller Inc.', 'purchase_price' => 1400.00, 'description' => 'Ergonomic task chairs with lumbar support.'],
            ['name' => 'Precision Torque Wrench Kit', 'serial' => 'TW-KIT-55', 'brand' => 'Snap-on', 'model' => 'ATECH3FR250B', 'category' => 'Manufacturing Tools', 'status' => 'active', 'location' => 'Workshop A', 'supplier' => 'Snap-on Incorporated', 'purchase_price' => 780.00, 'description' => 'Calibrated torque wrench set for assembly work.'],
        ];

        foreach ($assets as $index => $data) {
            $category = $categories->firstWhere('name', $data['category']);
            $status = $data['status'];

            $assetTag = 'AST-' . date('Y', strtotime("-{$index} days")) . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);

            $asset = Asset::create([
                'name' => $data['name'],
                'asset_tag' => $assetTag,
                'serial' => $data['serial'],
                'category_id' => $category?->id ?? $categories->first()->id,
                'status' => $status,
                'brand' => $data['brand'],
                'model' => $data['model'],
                'location' => $data['location'],
                'supplier' => $data['supplier'],
                'purchase_price' => $data['purchase_price'],
                'description' => $data['description'],
                'purchase_date' => now()->subDays(rand(30, 730)),
                'created_by' => $admin->id,
                'archived_at' => $status === 'archived' ? now()->subDays(rand(10, 90)) : null,
                'archived_reason' => $data['archived_reason'] ?? null,
            ]);
        }
    }
}
