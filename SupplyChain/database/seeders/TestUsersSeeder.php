<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Manufacturer;
use App\Models\Wholesaler;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run()
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@chicaura.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create Supplier
        $supplier = User::create([
            'name' => 'Supplier User',
            'email' => 'supplier@chicaura.com',
            'password' => Hash::make('password123'),
            'role' => 'supplier',
        ]);

        Supplier::create([
            'user_id' => $supplier->id,
            'business_address' => '123 Supplier St',
            'phone' => '1234567890',
            'license_document' => 'test.pdf',
            'materials_supplied' => json_encode(['Fabric', 'Buttons', 'Zippers']),
        ]);

        // Create Manufacturer
        $manufacturer = User::create([
            'name' => 'Manufacturer User',
            'email' => 'divinepraise699@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'manufacturer',
        ]);

        Manufacturer::create([
            'user_id' => $manufacturer->id,
            'business_address' => '456 Manufacturer Ave',
            'phone' => '0987654321',
            'license_document' => 'test.pdf',
            'production_capacity' => 1000,
            'specialization' => json_encode(['Dresses', 'Tops', 'Pants']),
        ]);

        // Create Wholesaler
        $wholesaler = User::create([
            'name' => 'Wholesaler User',
            'email' => 'praised854@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'wholesaler',
        ]);

        Wholesaler::create([
            'user_id' => $wholesaler->id,
            'business_address' => '789 Wholesaler Blvd',
            'phone' => '5555555555',
            'license_document' => 'test.pdf',
            'business_type' => 'Retail Chain',
            'preferred_categories' => json_encode(['Women\'s Wear', 'Men\'s Wear']),
            'monthly_order_volume' => 500,
        ]);
    }
} 