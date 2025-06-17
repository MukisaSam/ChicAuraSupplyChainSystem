<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\SupplyRequest;
use App\Models\SuppliedItem;
use App\Models\PriceNegotiation;
use Illuminate\Support\Facades\Hash;

class SupplierDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample items
        $items = [
            [
                'name' => 'Premium Cotton Fabric',
                'description' => 'High-quality cotton fabric for premium clothing',
                'category' => 'Fabric',
                'material' => 'Cotton',
                'base_price' => 2.50,
                'size_range' => 'Standard',
                'color_options' => 'White, Black, Blue, Red',
                'stock_quantity' => 10000,
                'is_active' => true,
            ],
            [
                'name' => 'Silk Fabric',
                'description' => 'Luxury silk fabric for high-end garments',
                'category' => 'Fabric',
                'material' => 'Silk',
                'base_price' => 15.00,
                'size_range' => 'Standard',
                'color_options' => 'White, Black, Gold, Silver',
                'stock_quantity' => 5000,
                'is_active' => true,
            ],
            [
                'name' => 'Zippers',
                'description' => 'High-quality metal zippers for clothing',
                'category' => 'Accessories',
                'material' => 'Metal',
                'base_price' => 0.75,
                'size_range' => 'Various',
                'color_options' => 'Silver, Gold, Black',
                'stock_quantity' => 50000,
                'is_active' => true,
            ],
            [
                'name' => 'Buttons',
                'description' => 'Decorative buttons for clothing',
                'category' => 'Accessories',
                'material' => 'Plastic',
                'base_price' => 0.25,
                'size_range' => 'Various',
                'color_options' => 'White, Black, Brown, Blue',
                'stock_quantity' => 100000,
                'is_active' => true,
            ],
            [
                'name' => 'Thread',
                'description' => 'High-strength sewing thread',
                'category' => 'Accessories',
                'material' => 'Polyester',
                'base_price' => 0.10,
                'size_range' => 'Standard',
                'color_options' => 'White, Black, Various',
                'stock_quantity' => 200000,
                'is_active' => true,
            ],
        ];

        foreach ($items as $itemData) {
            Item::create($itemData);
        }

        // Create sample supplier user
        $supplierUser = User::create([
            'name' => 'John Supplier',
            'email' => 'supplier@example.com',
            'password' => Hash::make('password'),
            'role' => 'supplier',
        ]);

        // Create supplier profile
        $supplier = Supplier::create([
            'user_id' => $supplierUser->id,
            'business_address' => '123 Supplier Street, Fabric City, FC 12345',
            'phone' => '+1-555-0123',
            'license_document' => 'license_doc.pdf',
            'materials_supplied' => ['Cotton', 'Silk', 'Zippers', 'Buttons', 'Thread'],
        ]);

        // Create sample supply requests
        $supplyRequests = [
            [
                'supplier_id' => $supplier->id,
                'item_id' => 1, // Premium Cotton Fabric
                'quantity' => 5000,
                'due_date' => now()->addDays(14),
                'status' => 'pending',
                'payment_type' => 'bank_transfer',
                'delivery_method' => 'delivery',
                'notes' => 'Need for summer collection production',
            ],
            [
                'supplier_id' => $supplier->id,
                'item_id' => 2, // Silk Fabric
                'quantity' => 2000,
                'due_date' => now()->addDays(21),
                'status' => 'approved',
                'payment_type' => 'credit',
                'delivery_method' => 'delivery',
                'notes' => 'Luxury line production',
            ],
            [
                'supplier_id' => $supplier->id,
                'item_id' => 3, // Zippers
                'quantity' => 15000,
                'due_date' => now()->addDays(7),
                'status' => 'in_progress',
                'payment_type' => 'cash',
                'delivery_method' => 'pickup',
                'notes' => 'Urgent order for jackets',
            ],
            [
                'supplier_id' => $supplier->id,
                'item_id' => 4, // Buttons
                'quantity' => 25000,
                'due_date' => now()->addDays(10),
                'status' => 'completed',
                'payment_type' => 'bank_transfer',
                'delivery_method' => 'delivery',
                'notes' => 'Shirt production line',
            ],
        ];

        foreach ($supplyRequests as $requestData) {
            SupplyRequest::create($requestData);
        }

        // Create sample supplied items
        $suppliedItems = [
            [
                'supplier_id' => $supplier->id,
                'item_id' => 1,
                'price' => 2.50,
                'delivered_quantity' => 5000,
                'delivery_date' => now()->subDays(5),
                'quality_rating' => 4.5,
                'status' => 'delivered',
            ],
            [
                'supplier_id' => $supplier->id,
                'item_id' => 2,
                'price' => 14.50,
                'delivered_quantity' => 2000,
                'delivery_date' => now()->subDays(10),
                'quality_rating' => 4.8,
                'status' => 'delivered',
            ],
            [
                'supplier_id' => $supplier->id,
                'item_id' => 3,
                'price' => 0.75,
                'delivered_quantity' => 15000,
                'delivery_date' => now()->subDays(15),
                'quality_rating' => 4.2,
                'status' => 'delivered',
            ],
            [
                'supplier_id' => $supplier->id,
                'item_id' => 4,
                'price' => 0.25,
                'delivered_quantity' => 25000,
                'delivery_date' => now()->subDays(20),
                'quality_rating' => 4.0,
                'status' => 'delivered',
            ],
            [
                'supplier_id' => $supplier->id,
                'item_id' => 5,
                'price' => 0.10,
                'delivered_quantity' => 50000,
                'delivery_date' => now()->subDays(25),
                'quality_rating' => 4.6,
                'status' => 'delivered',
            ],
        ];

        foreach ($suppliedItems as $itemData) {
            SuppliedItem::create($itemData);
        }

        // Create sample price negotiations
        $negotiations = [
            [
                'supply_request_id' => 2, // Silk Fabric request
                'initial_price' => 15.00,
                'counter_price' => 14.50,
                'status' => 'counter_offered',
                'notes' => 'Bulk order discount requested',
                'negotiation_date' => now()->subDays(5),
            ],
            [
                'supply_request_id' => 3, // Zippers request
                'initial_price' => 0.75,
                'status' => 'pending',
                'negotiation_date' => now()->subDays(2),
            ],
        ];

        foreach ($negotiations as $negotiationData) {
            PriceNegotiation::create($negotiationData);
        }

        $this->command->info('Supplier sample data seeded successfully!');
        $this->command->info('Supplier login: supplier@example.com / password');
    }
} 