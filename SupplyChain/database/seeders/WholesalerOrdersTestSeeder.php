<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use Carbon\Carbon;

class WholesalerOrdersTestSeeder extends Seeder
{
    public function run(): void
    {
        // Get test users
        $manufacturer = User::where('email', 'divinepraise699@gmail.com')->first();
        $wholesaler = User::where('email', 'praised854@gmail.com')->first();

        // Get finished products
        $finishedProducts = Item::where('type', 'finished_product')->take(3)->get();
        if ($finishedProducts->isEmpty() || !$manufacturer || !$wholesaler) {
            echo "Required test users or finished products not found.\n";
            return;
        }

        $orderDate = Carbon::now()->subWeeks(2);

        // Create a manufacturer order
        $manufacturerOrder = Order::create([
            'order_number' => 'MFG-' . strtoupper(uniqid()),
            'manufacturer_id' => $manufacturer->manufacturer->id ?? null,
            'wholesaler_id' => $wholesaler->wholesaler->id ?? null,
            'status' => 'confirmed',
            'total_amount' => 0,
            'order_date' => $orderDate,
            'created_at' => $orderDate,
            'updated_at' => $orderDate,
            'delivery_address' => '123 Manufacturer Lane, Test City',
        ]);
        $total = 0;
        foreach ($finishedProducts as $item) {
            $qty = rand(1, 5);
            OrderItem::create([
                'order_id' => $manufacturerOrder->id,
                'item_id' => $item->id,
                'quantity' => $qty,
                'unit_price' => $item->base_price,
                'total_price' => $item->base_price * $qty,
            ]);
            $total += $item->base_price * $qty;
        }
        $manufacturerOrder->total_amount = $total;
        $manufacturerOrder->save();

        // Create a wholesaler order
        $wholesalerOrder = Order::create([
            'order_number' => 'WHL-' . strtoupper(uniqid()),
            'wholesaler_id' => $wholesaler->wholesaler->id ?? null,
            'manufacturer_id' => $manufacturer->manufacturer->id ?? null,
            'status' => 'confirmed',
            'total_amount' => 0,
            'order_date' => $orderDate,
            'created_at' => $orderDate,
            'updated_at' => $orderDate,
            'delivery_address' => '456 Wholesaler Blvd, Test City',
        ]);
        $total = 0;
        foreach ($finishedProducts as $item) {
            $qty = rand(1, 5);
            OrderItem::create([
                'order_id' => $wholesalerOrder->id,
                'item_id' => $item->id,
                'quantity' => $qty,
                'unit_price' => $item->base_price,
                'total_price' => $item->base_price * $qty,
            ]);
            $total += $item->base_price * $qty;
        }
        $wholesalerOrder->total_amount = $total;
        $wholesalerOrder->save();
    }
} 