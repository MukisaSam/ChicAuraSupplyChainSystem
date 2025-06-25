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

        // Create sample items
        $items = [
            [
                'name' => 'Silk Evening Dress',
                'description' => 'Elegant silk evening dress with intricate beading',
                'category' => 'Dresses',
                'material' => 'Silk',
                'base_price' => 299.99,
                'size_range' => 'XS, S, M, L, XL',
                'color_options' => 'Black, Red, Navy',
                'stock_quantity' => 50,
                'image_url' => '/images/silk-dress.jpg',
            ],
            [
                'name' => 'Cotton Summer Blouse',
                'description' => 'Lightweight cotton blouse perfect for summer',
                'category' => 'Tops',
                'material' => 'Cotton',
                'base_price' => 89.99,
                'size_range' => 'XS, S, M, L, XL',
                'color_options' => 'White, Blue, Pink',
                'stock_quantity' => 100,
                'image_url' => '/images/cotton-blouse.jpg',
            ],
            [
                'name' => 'Wool Winter Coat',
                'description' => 'Warm wool coat for cold weather',
                'category' => 'Outerwear',
                'material' => 'Wool',
                'base_price' => 199.99,
                'size_range' => 'S, M, L, XL',
                'color_options' => 'Black, Gray, Brown',
                'stock_quantity' => 30,
                'image_url' => '/images/wool-coat.jpg',
            ],
            [
                'name' => 'Denim Jeans',
                'description' => 'Classic denim jeans with perfect fit',
                'category' => 'Bottoms',
                'material' => 'Denim',
                'base_price' => 79.99,
                'size_range' => '28-36',
                'color_options' => 'Blue, Black, Gray',
                'stock_quantity' => 150,
                'image_url' => '/images/denim-jeans.jpg',
            ],
        ];

        foreach ($items as $itemData) {
            Item::create($itemData);
        }

        // Create sample supply requests
        $supplyRequests = [
            [
                'supplier_id' => $supplierModel->id,
                'item_id' => 1,
                'quantity' => 25,
                'due_date' => now()->addDays(14),
                'status' => 'approved',
                'payment_type' => 'bank_transfer',
                'delivery_method' => 'delivery',
                'notes' => 'Need for upcoming fashion show',
            ],
            [
                'supplier_id' => $supplierModel->id,
                'item_id' => 2,
                'quantity' => 50,
                'due_date' => now()->addDays(7),
                'status' => 'pending',
                'payment_type' => 'cash',
                'delivery_method' => 'pickup',
                'notes' => 'Summer collection preparation',
            ],
            [
                'supplier_id' => $supplierModel->id,
                'item_id' => 3,
                'quantity' => 15,
                'due_date' => now()->addDays(21),
                'status' => 'in_progress',
                'payment_type' => 'credit',
                'delivery_method' => 'delivery',
                'notes' => 'Winter season preparation',
            ],
        ];

        foreach ($supplyRequests as $requestData) {
            SupplyRequest::create($requestData);
        }

        // Create sample supplied items
        $suppliedItems = [
            [
                'supplier_id' => $supplierModel->id,
                'item_id' => 1,
                'price' => 285.00,
                'delivered_quantity' => 20,
                'delivery_date' => now()->subDays(5),
                'quality_rating' => 5,
                'status' => 'delivered',
            ],
            [
                'supplier_id' => $supplierModel->id,
                'item_id' => 2,
                'price' => 85.00,
                'delivered_quantity' => 40,
                'delivery_date' => now()->subDays(3),
                'quality_rating' => 4,
                'status' => 'delivered',
            ],
        ];

        foreach ($suppliedItems as $suppliedItemData) {
            SuppliedItem::create($suppliedItemData);
        }

        // Create sample price negotiations
        $priceNegotiations = [
            [
                'supply_request_id' => 1,
                'proposed_price' => 285.00,
                'counter_price' => 290.00,
                'status' => 'accepted',
                'notes' => 'Bulk order discount applied',
            ],
            [
                'supply_request_id' => 2,
                'proposed_price' => 85.00,
                'counter_price' => null,
                'status' => 'pending',
                'notes' => 'Awaiting supplier response',
            ],
        ];

        foreach ($priceNegotiations as $negotiationData) {
            PriceNegotiation::create($negotiationData);
        }

        // Call additional seeders

    }

}
