<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Manufacturer;
use App\Models\Wholesaler;
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

        Supplier::create([
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

        Manufacturer::create([
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

        Wholesaler::create([
            'user_id' => $wholesaler->id,
            'business_address' => '789 Wholesaler Blvd, City',
            'phone' => '5555555555',
            'license_document' => 'wholesaler_license.pdf',
            'business_type' => 'Retail Chain',
            'preferred_categories' => json_encode(['casual', 'formal', 'sports']),
            'monthly_order_volume' => 500,
        ]);
    }
}
