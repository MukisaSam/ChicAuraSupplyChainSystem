<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get users with 'supplier' role
        $supplierUsers = User::where('role', 'supplier')->get();

        $suppliers = [
            [
                'user_id' => $supplierUsers->random()->id,
                'business_address' => '234 Supply Chain Road, Ntinda, Kampala, Uganda',
                'phone' => '+256708901234',
                'license_document' => 'supplier_license_001.pdf',
                'document_path' => 'documents/licenses/supplier_license_001.pdf',
                'materials_supplied' => json_encode(['Raw Materials', 'Steel', 'Aluminum', 'Copper']),
            ],
            [
                'user_id' => $supplierUsers->random()->id,
                'business_address' => '567 Logistics Avenue, Bugolobi, Kampala, Uganda',
                'phone' => '+256709012345',
                'license_document' => 'supplier_license_002.pdf',
                'document_path' => 'documents/licenses/supplier_license_002.pdf',
                'materials_supplied' => json_encode(['Textiles', 'Cotton', 'Synthetic Fabrics', 'Dyes']),
            ],
            [
                'user_id' => $supplierUsers->random()->id,
                'business_address' => '890 Distribution Center, Nakawa, Kampala, Uganda',
                'phone' => '+256710123456',
                'license_document' => 'supplier_license_003.pdf',
                'document_path' => 'documents/licenses/supplier_license_003.pdf',
                'materials_supplied' => json_encode(['Electronic Components', 'Semiconductors', 'Circuits', 'Batteries']),
            ],
            [
                'user_id' => $supplierUsers->random()->id,
                'business_address' => '123 Wholesale Market, Owino, Kampala, Uganda',
                'phone' => '+256711234567',
                'license_document' => 'supplier_license_004.pdf',
                'document_path' => 'documents/licenses/supplier_license_004.pdf',
                'materials_supplied' => json_encode(['Food Ingredients', 'Spices', 'Preservatives', 'Packaging Materials']),
            ],
            [
                'user_id' => $supplierUsers->random()->id,
                'business_address' => '456 Timber Yard, Bweyogerere, Kampala, Uganda',
                'phone' => '+256712345678',
                'license_document' => 'supplier_license_005.pdf',
                'document_path' => 'documents/licenses/supplier_license_005.pdf',
                'materials_supplied' => json_encode(['Wood', 'Timber', 'Plywood', 'Hardware']),
            ],
            [
                'user_id' => $supplierUsers->random()->id,
                'business_address' => '789 Chemical Supply Hub, Namanve, Kampala, Uganda',
                'phone' => '+256713456789',
                'license_document' => 'supplier_license_006.pdf',
                'document_path' => 'documents/licenses/supplier_license_006.pdf',
                'materials_supplied' => json_encode(['Chemicals', 'Solvents', 'Acids', 'Industrial Cleaners']),
            ],
            [
                'user_id' => $supplierUsers->random()->id,
                'business_address' => '321 Agricultural Supplies, Wakiso, Uganda',
                'phone' => '+256714567890',
                'license_document' => 'supplier_license_007.pdf',
                'document_path' => 'documents/licenses/supplier_license_007.pdf',
                'materials_supplied' => json_encode(['Seeds', 'Fertilizers', 'Pesticides', 'Farm Equipment']),
            ],
            [
                'user_id' => $supplierUsers->random()->id,
                'business_address' => '654 Pharmaceutical Supply, Kololo, Kampala, Uganda',
                'phone' => '+256715678901',
                'license_document' => 'supplier_license_008.pdf',
                'document_path' => 'documents/licenses/supplier_license_008.pdf',
                'materials_supplied' => json_encode(['Active Ingredients', 'Excipients', 'Packaging', 'Medical Supplies']),
            ],
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::create($supplierData);
        }
    }
}