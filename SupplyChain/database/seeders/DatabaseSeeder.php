<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Manufacturer;
use App\Models\Wholesaler;
use App\Models\Item;
use App\Models\SupplyRequest;
use App\Models\SuppliedItem;
use App\Models\PriceNegotiation;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@chicaura.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create supplier user
        $supplier = User::create([
            'name' => 'Test Supplier',
            'email' => 'supplier@chicaura.com',
            'password' => Hash::make('password123'),
            'role' => 'supplier',
        ]);

        $supplierModel = Supplier::create([
            'user_id' => $supplier->id,
            'business_address' => '123 Supplier Street, City',
            'phone' => '1234567890',
            'license_document' => 'supplier_license.pdf',
            'materials_supplied' => json_encode(['cotton', 'silk', 'wool']),
        ]);

        // Create manufacturer user
        $manufacturer = User::create([
            'name' => 'Test Manufacturer',
            'email' => 'manufacturer@chicaura.com',
            'password' => Hash::make('password123'),
            'role' => 'manufacturer',
        ]);

        $manufacturerModel = Manufacturer::create([
            'user_id' => $manufacturer->id,
            'business_address' => '456 Manufacturer Ave, City',
            'phone' => '0987654321',
            'license_document' => 'manufacturer_license.pdf',
            'production_capacity' => 1000,
            'specialization' => json_encode(['dresses', 'tops', 'pants']),
        ]);

        // Create wholesaler user
        $wholesaler = User::create([
            'name' => 'Test Wholesaler',
            'email' => 'wholesaler@chicaura.com',
            'password' => Hash::make('password123'),
            'role' => 'wholesaler',
        ]);

        $wholesalerModel = Wholesaler::create([
            'user_id' => $wholesaler->id,
            'business_address' => '789 Wholesaler Blvd, City',
            'phone' => '5555555555',
            'license_document' => 'wholesaler_license.pdf',
            'business_type' => 'Retail Chain',
            'preferred_categories' => json_encode(['casual', 'formal', 'sports']),
            'monthly_order_volume' => 500,
        ]);

        // --- Manufacturer Test Data ---
        // Create items needed for manufacturer test data
        $item1 = \App\Models\Item::create([
            'name' => 'Silk Evening Dress',
            'description' => 'Elegant silk evening dress with intricate beading',
            'category' => 'Dresses',
            'material' => 'Silk',
            'base_price' => 299.99,
            'size_range' => 'XS, S, M, L, XL',
            'color_options' => 'Black, Red, Navy',
            'stock_quantity' => 50,
            'image_url' => '/images/silk-dress.jpg',
        ]);
        $item2 = \App\Models\Item::create([
            'name' => 'Cotton Summer Blouse',
            'description' => 'Lightweight cotton blouse perfect for summer',
            'category' => 'Tops',
            'material' => 'Cotton',
            'base_price' => 89.99,
            'size_range' => 'XS, S, M, L, XL',
            'color_options' => 'White, Blue, Pink',
            'stock_quantity' => 100,
            'image_url' => '/images/cotton-blouse.jpg',
        ]);
        $item3 = \App\Models\Item::create([
            'name' => 'Wool Winter Coat',
            'description' => 'Warm wool coat for cold weather',
            'category' => 'Outerwear',
            'material' => 'Wool',
            'base_price' => 199.99,
            'size_range' => 'S, M, L, XL',
            'color_options' => 'Black, Gray, Brown',
            'stock_quantity' => 30,
            'image_url' => '/images/wool-coat.jpg',
        ]);
        $item4 = \App\Models\Item::create([
            'name' => 'Denim Jeans',
            'description' => 'Classic denim jeans with perfect fit',
            'category' => 'Bottoms',
            'material' => 'Denim',
            'base_price' => 79.99,
            'size_range' => '28-36',
            'color_options' => 'Blue, Black, Gray',
            'stock_quantity' => 150,
            'image_url' => '/images/denim-jeans.jpg',
        ]);

        // Create workforce for manufacturer
        $workforce1 = \App\Models\Workforce::create([
            'fullname' => 'Alice Johnson',
            'email' => 'alice.johnson@chicaura.com',
            'contact_info' => '555-111-2222',
            'address' => '123 Worker Lane',
            'job_role' => 'Line Supervisor',
            'status' => 'active',
            'hire_date' => now()->subYears(2),
            'salary' => 35000,
            'manufacturer_id' => $manufacturerModel->id,
        ]);
        $workforce2 = \App\Models\Workforce::create([
            'fullname' => 'Bob Smith',
            'email' => 'bob.smith@chicaura.com',
            'contact_info' => '555-333-4444',
            'address' => '456 Worker Ave',
            'job_role' => 'Quality Inspector',
            'status' => 'active',
            'hire_date' => now()->subYear(),
            'salary' => 30000,
            'manufacturer_id' => $manufacturerModel->id,
        ]);

        // Create warehouse for manufacturer
        $warehouse = \App\Models\Warehouse::create([
            'location' => 'Main Industrial Park',
            'capacity' => 5000,
            'manufacturer_id' => $manufacturerModel->id,
        ]);

        // Create work orders for manufacturer
        $workOrder1 = \App\Models\WorkOrder::create([
            'product_id' => $item1->id, // Silk Evening Dress
            'quantity' => 100,
            'status' => 'InProgress',
            'scheduled_start' => now()->subDays(10),
            'scheduled_end' => now()->addDays(5),
            'actual_start' => now()->subDays(9),
            'actual_end' => null,
            'notes' => 'Urgent order for new collection',
        ]);
        $workOrder2 = \App\Models\WorkOrder::create([
            'product_id' => $item2->id, // Cotton Summer Blouse
            'quantity' => 200,
            'status' => 'Planned',
            'scheduled_start' => now()->addDays(2),
            'scheduled_end' => now()->addDays(12),
            'actual_start' => null,
            'actual_end' => null,
            'notes' => 'Scheduled for next production cycle',
        ]);

        // Production schedules
        \App\Models\ProductionSchedule::create([
            'work_order_id' => $workOrder1->id,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(5),
            'status' => 'InProgress',
            'notes' => 'Running on time',
        ]);
        \App\Models\ProductionSchedule::create([
            'work_order_id' => $workOrder2->id,
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(12),
            'status' => 'Planned',
            'notes' => 'Awaiting materials',
        ]);

        // Production costs
        \App\Models\ProductionCost::create([
            'work_order_id' => $workOrder1->id,
            'material_cost' => 5000,
            'labor_cost' => 2000,
            'overhead_cost' => 1000,
            'total_cost' => 8000,
        ]);
        \App\Models\ProductionCost::create([
            'work_order_id' => $workOrder2->id,
            'material_cost' => 7000,
            'labor_cost' => 2500,
            'overhead_cost' => 1200,
            'total_cost' => 10700,
        ]);

        // Bill of Materials for each product
        $bom1 = \App\Models\BillOfMaterial::create([
            'product_id' => $item1->id,
        ]);
        $bom2 = \App\Models\BillOfMaterial::create([
            'product_id' => $item2->id,
        ]);
        // Components for BOM 1
        \App\Models\BillOfMaterialComponent::create([
            'bom_id' => $bom1->id,
            'raw_item_id' => $item3->id, // Wool Winter Coat as a component (example)
            'quantity' => 10,
        ]);
        \App\Models\BillOfMaterialComponent::create([
            'bom_id' => $bom1->id,
            'raw_item_id' => $item4->id, // Denim Jeans as a component (example)
            'quantity' => 5,
        ]);
        // Components for BOM 2
        \App\Models\BillOfMaterialComponent::create([
            'bom_id' => $bom2->id,
            'raw_item_id' => $item1->id, // Silk Evening Dress as a component (example)
            'quantity' => 8,
        ]);
        \App\Models\BillOfMaterialComponent::create([
            'bom_id' => $bom2->id,
            'raw_item_id' => $item3->id, // Wool Winter Coat as a component (example)
            'quantity' => 3,
        ]);

        // Quality checks for work order 1
        \App\Models\QualityCheck::create([
            'work_order_id' => $workOrder1->id,
            'stage' => 'cutting',
            'result' => 'pass',
            'checked_by' => $workforce2->id,
            'checked_at' => now()->subDays(8),
            'notes' => 'All pieces cut accurately',
        ]);
        \App\Models\QualityCheck::create([
            'work_order_id' => $workOrder1->id,
            'stage' => 'sewing',
            'result' => 'fail',
            'checked_by' => $workforce2->id,
            'checked_at' => now()->subDays(5),
            'notes' => 'Some stitching errors found',
        ]);

        // Downtime log for work order 1
        \App\Models\DowntimeLog::create([
            'work_order_id' => $workOrder1->id,
            'start_time' => now()->subDays(7)->setTime(10, 0),
            'end_time' => now()->subDays(7)->setTime(12, 0),
            'reason' => 'Machine maintenance',
            'notes' => 'Routine maintenance of sewing machines',
        ]);

        // Call additional seeders

    }

}
