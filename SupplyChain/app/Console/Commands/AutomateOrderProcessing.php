<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Notifications\OrderStatusUpdatedNotification;
use Illuminate\Support\Facades\DB;

class AutomateOrderProcessing extends Command
{
    protected $signature = 'orders:automate-processing';
    protected $description = 'Automate processing of all pending orders for manufacturers and wholesalers and send notifications.';

    public function handle()
    {
        $pendingOrders = Order::where('status', 'pending')->get();
        $processedCount = 0;

        foreach ($pendingOrders as $order) {
            // Example logic: move to confirmed, then in_production
            $oldStatus = $order->status;
            $order->status = 'confirmed';
            $order->save();

            // Send notification to wholesaler or manufacturer
            if ($order->wholesaler && $order->wholesaler->user) {
                $order->wholesaler->user->notify(new OrderStatusUpdatedNotification($order, 'confirmed'));
            }
            if ($order->manufacturer && $order->manufacturer->user) {
                $order->manufacturer->user->notify(new OrderStatusUpdatedNotification($order, 'confirmed'));
            }

            // Inventory check and supply request (reuse logic)
            $this->checkInventoryAndCreateSupplyRequests($order);

            $processedCount++;
        }

        $this->info("Processed {$processedCount} pending orders.");
        return 0;
    }

    private function checkInventoryAndCreateSupplyRequests($order)
    {
        foreach ($order->orderItems as $orderItem) {
            $item = $orderItem->item;

            // Check if we have enough inventory
            if ($item->stock_quantity < $orderItem->quantity) {
                // Find suppliers for this item
                $suppliers = \App\Models\Supplier::whereHas('suppliedItems', function($query) use ($item) {
                    $query->where('item_id', $item->id);
                })->get();

                if ($suppliers->count() > 0) {
                    // Create supply request for the first available supplier
                    $supplier = $suppliers->first();
                    $neededQuantity = $orderItem->quantity - $item->stock_quantity;

                    \App\Models\SupplyRequest::create([
                        'supplier_id' => $supplier->id,
                        'item_id' => $item->id,
                        'quantity' => $neededQuantity,
                        'due_date' => now()->addDays(7),
                        'status' => 'pending',
                        'notes' => "Auto-generated for order #{$order->order_number}",
                    ]);
                }
            }
        }
    }
} 