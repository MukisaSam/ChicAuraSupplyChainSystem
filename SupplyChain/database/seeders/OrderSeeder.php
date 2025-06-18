<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wholesaler;
use App\Models\Item;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wholesalers = Wholesaler::all();
        $items = Item::all();
        
        if ($wholesalers->isEmpty() || $items->isEmpty()) {
            return;
        }
        
        $statuses = ['pending', 'confirmed', 'in_production', 'shipped', 'delivered'];
        $paymentMethods = ['cash', 'credit', 'bank_transfer', 'installment'];
        
        foreach ($wholesalers as $wholesaler) {
            for ($i = 0; $i < 10; $i++) {
                $order = Order::create([
                    'wholesaler_id' => $wholesaler->id,
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'status' => $statuses[array_rand($statuses)],
                    'order_date' => now()->subDays(rand(1, 30)),
                    'total_amount' => 0, // Will be calculated
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'shipping_address' => '123 Wholesale Street, Business District, City, Country',
                    'notes' => rand(0, 1) ? 'Please deliver during business hours.' : null,
                    'estimated_delivery' => now()->addDays(rand(7, 21)),
                    'actual_delivery' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                ]);
                
                // Create 1-3 order items per order
                $numItems = rand(1, 3);
                $totalAmount = 0;
                
                for ($j = 0; $j < $numItems; $j++) {
                    $item = $items->random();
                    $quantity = rand(10, 100);
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
                
                // Update order total
                $order->update(['total_amount' => $totalAmount]);
            }
        }
    }
}
