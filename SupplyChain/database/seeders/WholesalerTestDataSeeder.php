<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wholesaler;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WholesalerTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $wholesalerUser = User::where('email', 'wholesaler@chicaura.com')->first();
        if (!$wholesalerUser) {
            $wholesalerUser = User::create([
                'name' => 'Wholesaler User',
                'email' => 'wholesaler@chicaura.com',
                'password' => bcrypt('password123'),
                'role' => 'wholesaler',
            ]);
        }
        $wholesaler = Wholesaler::where('user_id', $wholesalerUser->id)->first();
        if (!$wholesaler) {
            $wholesaler = Wholesaler::create([
                'user_id' => $wholesalerUser->id,
                'business_address' => '789 Wholesaler Blvd, Business District, City, Country',
                'phone' => '+1-555-0123',
                'license_document' => 'wholesaler_license.pdf',
                'business_type' => 'Retail Chain',
                'preferred_categories' => json_encode(['Women\'s Wear', 'Men\'s Wear', 'Accessories']),
                'monthly_order_volume' => 500,
            ]);
        }
        $items = Item::all();
        if ($items->isEmpty()) {
            $items = collect([
                Item::create([
                    'name' => 'Premium Cotton T-Shirt',
                    'description' => 'High-quality cotton t-shirt with comfortable fit',
                    'category' => 'Tops',
                    'material' => 'Cotton',
                    'base_price' => 18.50,
                    'size_range' => 'S-XXL',
                    'color_options' => 'White, Black, Blue, Red, Gray',
                    'stock_quantity' => 1000,
                    'is_active' => true,
                ]),
                Item::create([
                    'name' => 'Classic Denim Jeans',
                    'description' => 'Classic denim jeans with modern styling',
                    'category' => 'Bottoms',
                    'material' => 'Denim',
                    'base_price' => 52.00,
                    'size_range' => '28-40',
                    'color_options' => 'Blue, Black, Gray, Light Blue',
                    'stock_quantity' => 800,
                    'is_active' => true,
                ]),
                Item::create([
                    'name' => 'Warm Hooded Sweatshirt',
                    'description' => 'Warm and comfortable hooded sweatshirt for cold weather',
                    'category' => 'Outerwear',
                    'material' => 'Cotton Blend',
                    'base_price' => 42.00,
                    'size_range' => 'S-XXL',
                    'color_options' => 'Gray, Black, Navy, Burgundy',
                    'stock_quantity' => 600,
                    'is_active' => true,
                ]),
                Item::create([
                    'name' => 'Elegant Summer Dress',
                    'description' => 'Light and breezy summer dress with floral pattern',
                    'category' => 'Dresses',
                    'material' => 'Polyester',
                    'base_price' => 65.00,
                    'size_range' => 'XS-L',
                    'color_options' => 'Floral, Blue, Pink, White',
                    'stock_quantity' => 400,
                    'is_active' => true,
                ]),
                Item::create([
                    'name' => 'Professional Polo Shirt',
                    'description' => 'Professional polo shirt perfect for business casual',
                    'category' => 'Tops',
                    'material' => 'Cotton',
                    'base_price' => 28.00,
                    'size_range' => 'S-XXL',
                    'color_options' => 'White, Blue, Red, Green, Black',
                    'stock_quantity' => 750,
                    'is_active' => true,
                ]),
                Item::create([
                    'name' => 'Casual Cargo Pants',
                    'description' => 'Comfortable cargo pants with multiple pockets',
                    'category' => 'Bottoms',
                    'material' => 'Cotton',
                    'base_price' => 38.00,
                    'size_range' => '30-42',
                    'color_options' => 'Khaki, Black, Olive, Navy',
                    'stock_quantity' => 500,
                    'is_active' => true,
                ]),
                Item::create([
                    'name' => 'Lightweight Cardigan',
                    'description' => 'Lightweight cardigan perfect for layering',
                    'category' => 'Outerwear',
                    'material' => 'Acrylic',
                    'base_price' => 35.00,
                    'size_range' => 'S-L',
                    'color_options' => 'Beige, Gray, Black, Pink',
                    'stock_quantity' => 300,
                    'is_active' => true,
                ]),
                Item::create([
                    'name' => 'Formal Blouse',
                    'description' => 'Elegant formal blouse for professional settings',
                    'category' => 'Tops',
                    'material' => 'Silk Blend',
                    'base_price' => 45.00,
                    'size_range' => 'XS-L',
                    'color_options' => 'White, Black, Blue, Cream',
                    'stock_quantity' => 350,
                    'is_active' => true,
                ])
            ]);
        }
        Order::where('wholesaler_id', $wholesaler->id)->delete();
        $statuses = ['pending', 'confirmed', 'in_production', 'shipped', 'delivered', 'cancelled'];
        $paymentMethods = ['cash on delivery', 'mobile money', 'bank_transfer'];
        $startDate = Carbon::now()->subDays(30);
        for ($day = 0; $day <= 30; $day++) {
            $currentDate = $startDate->copy()->addDays($day);
            $ordersPerDay = max(1, min(8, intval(1 + ($day / 30) * 7)));
            if ($currentDate->isWeekend()) {
                $ordersPerDay = max(1, intval($ordersPerDay * 0.6));
            }
            for ($i = 0; $i < $ordersPerDay; $i++) {
                $orderTime = $currentDate->copy()->setTime(
                    rand(9, 17),
                    rand(0, 59),
                    rand(0, 59)
                );
                $statusWeights = [
                    'pending' => $day < 3 ? 40 : 20,
                    'confirmed' => $day < 7 ? 30 : 25,
                    'in_production' => $day < 14 ? 20 : 30,
                    'shipped' => $day < 21 ? 10 : 20,
                    'delivered' => $day < 30 ? 0 : 5,
                    'cancelled' => 5
                ];
                $status = $this->weightedRandom($statusWeights);
                $order = Order::create([
                    'wholesaler_id' => $wholesaler->id,
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'status' => $status,
                    'order_date' => $orderTime,
                    'total_amount' => 0,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'shipping_address' => '789 Wholesaler Blvd, Business District, City, Country',
                    'notes' => rand(0, 1) ? 'Please deliver during business hours.' : null,
                    'estimated_delivery' => $orderTime->copy()->addDays(rand(7, 21)),
                    'actual_delivery' => $status === 'delivered' ? $orderTime->copy()->addDays(rand(10, 25)) : null,
                ]);
                $numItems = rand(1, 4);
                $totalAmount = 0;
                for ($j = 0; $j < $numItems; $j++) {
                    $item = $items->random();
                    $quantity = rand(20, 200);
                    $unitPrice = $item->base_price;
                    $totalPrice = $quantity * $unitPrice;
                    $totalAmount += $totalPrice;
                    OrderItem::create([
                        'order_id' => $order->id,
                        'item_id' => $item->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'notes' => rand(0, 1) ? 'Bulk order discount applied.' : null,
                    ]);
                }
                $order->update(['total_amount' => $totalAmount]);
            }
        }
        $this->command->info('Wholesaler test data created successfully!');
        $this->command->info('Total orders created: ' . Order::where('wholesaler_id', $wholesaler->id)->count());
    }
    private function weightedRandom($weights)
    {
        $total = array_sum($weights);
        $random = rand(1, $total);
        foreach ($weights as $key => $weight) {
            $random -= $weight;
            if ($random <= 0) {
                return $key;
            }
        }
        return array_key_first($weights);
    }
}
