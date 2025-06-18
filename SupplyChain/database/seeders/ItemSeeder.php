<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Cotton T-Shirt',
                'description' => 'Premium cotton t-shirt with comfortable fit',
                'category' => 'Tops',
                'material' => 'Cotton',
                'base_price' => 15.00,
                'size_range' => 'S-XXL',
                'color_options' => 'White, Black, Blue, Red',
                'stock_quantity' => 500,
                'is_active' => true,
            ],
            [
                'name' => 'Denim Jeans',
                'description' => 'Classic denim jeans with modern styling',
                'category' => 'Bottoms',
                'material' => 'Denim',
                'base_price' => 45.00,
                'size_range' => '28-40',
                'color_options' => 'Blue, Black, Gray',
                'stock_quantity' => 300,
                'is_active' => true,
            ],
            [
                'name' => 'Hooded Sweatshirt',
                'description' => 'Warm and comfortable hooded sweatshirt',
                'category' => 'Outerwear',
                'material' => 'Cotton Blend',
                'base_price' => 35.00,
                'size_range' => 'S-XXL',
                'color_options' => 'Gray, Black, Navy',
                'stock_quantity' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Summer Dress',
                'description' => 'Light and breezy summer dress',
                'category' => 'Dresses',
                'material' => 'Polyester',
                'base_price' => 55.00,
                'size_range' => 'XS-L',
                'color_options' => 'Floral, Blue, Pink',
                'stock_quantity' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Polo Shirt',
                'description' => 'Professional polo shirt for business casual',
                'category' => 'Tops',
                'material' => 'Cotton',
                'base_price' => 25.00,
                'size_range' => 'S-XXL',
                'color_options' => 'White, Blue, Red, Green',
                'stock_quantity' => 400,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
} 