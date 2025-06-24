<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // Raw Materials
            [
                'name' => 'Premium Cotton Fabric',
                'description' => 'High-quality 100% cotton fabric suitable for premium clothing items. Soft, breathable, and durable.',
                'category' => 'Raw Materials',
                'material' => 'Cotton',
                'base_price' => 15.50,
                'stock_quantity' => 500,
                'size_range' => 'Rolls (50m each)',
                'color_options' => 'White, Black, Navy, Gray',
                'is_active' => true,
            ],
            [
                'name' => 'Silk Fabric',
                'description' => 'Luxury silk fabric for high-end garments. Smooth texture and elegant appearance.',
                'category' => 'Raw Materials',
                'material' => 'Silk',
                'base_price' => 45.00,
                'stock_quantity' => 200,
                'size_range' => 'Rolls (25m each)',
                'color_options' => 'Ivory, Black, Red, Gold',
                'is_active' => true,
            ],
            [
                'name' => 'Polyester Thread',
                'description' => 'Strong polyester thread for stitching. Available in various colors and thicknesses.',
                'category' => 'Threads',
                'material' => 'Polyester',
                'base_price' => 8.75,
                'stock_quantity' => 1000,
                'size_range' => 'Spools (500m each)',
                'color_options' => 'White, Black, Red, Blue, Green, Yellow',
                'is_active' => true,
            ],
            [
                'name' => 'Cotton Thread',
                'description' => 'Natural cotton thread for delicate fabrics. Soft and gentle on materials.',
                'category' => 'Threads',
                'material' => 'Cotton',
                'base_price' => 12.25,
                'stock_quantity' => 750,
                'size_range' => 'Spools (300m each)',
                'color_options' => 'White, Black, Brown, Beige',
                'is_active' => true,
            ],
            [
                'name' => 'Fabric Dye - Blue',
                'description' => 'High-quality fabric dye in vibrant blue color. Suitable for cotton and synthetic fabrics.',
                'category' => 'Dyes',
                'material' => 'Chemical',
                'base_price' => 25.00,
                'stock_quantity' => 50,
                'size_range' => 'Bottles (1L each)',
                'color_options' => 'Blue',
                'is_active' => true,
            ],
            [
                'name' => 'Fabric Dye - Red',
                'description' => 'High-quality fabric dye in vibrant red color. Suitable for cotton and synthetic fabrics.',
                'category' => 'Dyes',
                'material' => 'Chemical',
                'base_price' => 25.00,
                'stock_quantity' => 45,
                'size_range' => 'Bottles (1L each)',
                'color_options' => 'Red',
                'is_active' => true,
            ],
            [
                'name' => 'Zippers - Metal',
                'description' => 'High-quality metal zippers for jackets and pants. Durable and smooth operation.',
                'category' => 'Accessories',
                'material' => 'Metal',
                'base_price' => 3.50,
                'stock_quantity' => 2000,
                'size_range' => '8", 10", 12", 14"',
                'color_options' => 'Silver, Gold, Black, White',
                'is_active' => true,
            ],
            [
                'name' => 'Buttons - Plastic',
                'description' => 'Plastic buttons in various sizes and colors. Lightweight and durable.',
                'category' => 'Accessories',
                'material' => 'Plastic',
                'base_price' => 0.75,
                'stock_quantity' => 5000,
                'size_range' => 'Small, Medium, Large',
                'color_options' => 'White, Black, Brown, Blue, Red',
                'is_active' => true,
            ],
            [
                'name' => 'Denim Fabric',
                'description' => 'Heavy-duty denim fabric for jeans and jackets. Durable and classic appearance.',
                'category' => 'Fabrics',
                'material' => 'Denim',
                'base_price' => 22.00,
                'stock_quantity' => 300,
                'size_range' => 'Rolls (30m each)',
                'color_options' => 'Blue, Black, Gray',
                'is_active' => true,
            ],
            [
                'name' => 'Wool Fabric',
                'description' => 'Warm wool fabric for winter clothing. Natural insulation and comfort.',
                'category' => 'Fabrics',
                'material' => 'Wool',
                'base_price' => 35.00,
                'stock_quantity' => 150,
                'size_range' => 'Rolls (20m each)',
                'color_options' => 'Gray, Black, Brown, Navy',
                'is_active' => true,
            ],
            // Finished Products
            [
                'name' => 'Men\'s Cotton T-Shirt',
                'description' => 'Comfortable men\'s t-shirt made from premium cotton. Classic fit and soft feel.',
                'category' => 'Finished Products',
                'material' => 'Cotton',
                'base_price' => 18.00,
                'stock_quantity' => 250,
                'size_range' => 'S, M, L, XL, XXL',
                'color_options' => 'White, Black, Gray, Navy, Red',
                'is_active' => true,
            ],
            [
                'name' => 'Women\'s Silk Blouse',
                'description' => 'Elegant women\'s blouse made from pure silk. Perfect for professional and casual wear.',
                'category' => 'Finished Products',
                'material' => 'Silk',
                'base_price' => 65.00,
                'stock_quantity' => 80,
                'size_range' => 'XS, S, M, L, XL',
                'color_options' => 'Ivory, Black, Red, Blue',
                'is_active' => true,
            ],
            [
                'name' => 'Denim Jeans',
                'description' => 'Classic denim jeans with modern fit. Durable and comfortable for everyday wear.',
                'category' => 'Clothing',
                'material' => 'Denim',
                'base_price' => 45.00,
                'stock_quantity' => 120,
                'size_range' => '28-36 (Waist), 30-34 (Length)',
                'color_options' => 'Blue, Black, Gray',
                'is_active' => true,
            ],
            [
                'name' => 'Wool Sweater',
                'description' => 'Warm wool sweater for cold weather. Natural insulation and stylish design.',
                'category' => 'Clothing',
                'material' => 'Wool',
                'base_price' => 55.00,
                'stock_quantity' => 60,
                'size_range' => 'S, M, L, XL',
                'color_options' => 'Gray, Black, Brown, Navy',
                'is_active' => true,
            ],
            [
                'name' => 'Cotton Dress',
                'description' => 'Elegant cotton dress suitable for various occasions. Comfortable and stylish.',
                'category' => 'Clothing',
                'material' => 'Cotton',
                'base_price' => 38.00,
                'stock_quantity' => 90,
                'size_range' => 'XS, S, M, L, XL',
                'color_options' => 'White, Black, Blue, Pink, Yellow',
                'is_active' => true,
            ],
            // Low stock items for testing alerts
            [
                'name' => 'Premium Linen Fabric',
                'description' => 'High-quality linen fabric for summer clothing. Breathable and lightweight.',
                'category' => 'Raw Materials',
                'material' => 'Linen',
                'base_price' => 28.00,
                'stock_quantity' => 8,
                'size_range' => 'Rolls (40m each)',
                'color_options' => 'Natural, White, Beige',
                'is_active' => true,
            ],
            [
                'name' => 'Leather Buttons',
                'description' => 'Premium leather buttons for high-end garments. Elegant and durable.',
                'category' => 'Accessories',
                'material' => 'Leather',
                'base_price' => 2.50,
                'stock_quantity' => 5,
                'size_range' => 'Medium, Large',
                'color_options' => 'Brown, Black, Tan',
                'is_active' => true,
            ],
            [
                'name' => 'Velvet Fabric',
                'description' => 'Luxury velvet fabric for evening wear. Soft texture and rich appearance.',
                'category' => 'Fabrics',
                'material' => 'Velvet',
                'base_price' => 42.00,
                'stock_quantity' => 12,
                'size_range' => 'Rolls (15m each)',
                'color_options' => 'Black, Red, Navy, Purple',
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }

        DB::table('order_items')->delete();
        DB::table('orders')->delete();
        DB::table('items')->delete();
        DB::table('items')->insert([
            [
                'name' => 'Classic White T-Shirt',
                'description' => 'A timeless white t-shirt made from 100% organic cotton.',
                'category' => 'Tops',
                'material' => 'Cotton',
                'base_price' => 12.99,
                'size_range' => 'S,M,L,XL',
                'color_options' => 'White',
                'stock_quantity' => 100,
                'image_url' => 'images/white-tshirt.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Denim Jeans',
                'description' => 'Classic blue denim jeans with a modern fit.',
                'category' => 'Bottoms',
                'material' => 'Denim',
                'base_price' => 29.99,
                'size_range' => '28,30,32,34,36',
                'color_options' => 'Blue',
                'stock_quantity' => 60,
                'image_url' => 'images/denim-jeans.jpg',
                'is_active' => true,
            ],
        ]);
    }
} 