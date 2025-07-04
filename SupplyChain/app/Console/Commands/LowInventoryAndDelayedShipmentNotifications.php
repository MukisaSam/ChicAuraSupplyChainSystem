<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use App\Notifications\LowInventoryNotification;
use App\Notifications\DelayedShipmentNotification;
use Carbon\Carbon;

class LowInventoryAndDelayedShipmentNotifications extends Command
{
    protected $signature = 'notify:low-inventory-delayed-shipments';
    protected $description = 'Send notifications for low inventory and delayed shipments.';

    public function handle()
    {
        // Low inventory notifications
        $lowStockItems = Item::where('stock_quantity', '<=', 10)->get();
        foreach ($lowStockItems as $item) {
            // Notify all manufacturers (or responsible users)
            $manufacturers = User::where('role', 'manufacturer')->get();
            foreach ($manufacturers as $user) {
                $user->notify(new LowInventoryNotification($item));
            }
        }

        // Delayed shipment notifications
        $delayedOrders = Order::whereIn('status', ['shipped'])
            ->where('estimated_delivery', '<', Carbon::now())
            ->get();
        foreach ($delayedOrders as $order) {
            if ($order->wholesaler && $order->wholesaler->user) {
                $order->wholesaler->user->notify(new DelayedShipmentNotification($order));
            }
        }

        $this->info('Low inventory and delayed shipment notifications sent.');
        return 0;
    }
} 