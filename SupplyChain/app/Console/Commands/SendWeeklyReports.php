<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Order;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\InventoryItem;
use App\Mail\WeeklyReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SendWeeklyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:send-weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and email weekly reports as CSV attachments to manufacturers and wholesalers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info('Starting to send weekly reports...');
            
            $this->sendManufacturerReports();
            $this->sendWholesalerReports();
            
            $this->info('Weekly reports sent successfully to manufacturers and wholesalers.');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error sending weekly reports: ' . $e->getMessage());
            \Log::error('Weekly reports error: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Send reports to all manufacturers
     */
    private function sendManufacturerReports()
    {
        $manufacturers = User::where('role', 'manufacturer')->get();
        $this->info('Found ' . $manufacturers->count() . ' manufacturers');
        
        foreach ($manufacturers as $user) {
            try {
                $this->info("Generating report for manufacturer: {$user->email}");
                $csv = $this->generateManufacturerReport($user);
                
                if ($csv) {
                    Mail::to($user->email)->send(new WeeklyReportMail($user, $csv, 'manufacturer'));
                    $this->info("Report sent to manufacturer: {$user->email}");
                }
            } catch (\Exception $e) {
                $this->error("Error sending report to {$user->email}: " . $e->getMessage());
                \Log::error("Manufacturer report error for {$user->email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Send reports to all wholesalers
     */
    private function sendWholesalerReports()
    {
        $wholesalers = User::where('role', 'wholesaler')->get();
        $this->info('Found ' . $wholesalers->count() . ' wholesalers');
        
        foreach ($wholesalers as $user) {
            try {
                $this->info("Generating report for wholesaler: {$user->email}");
                $csv = $this->generateWholesalerReport($user);
                
                if ($csv) {
                    Mail::to($user->email)->send(new WeeklyReportMail($user, $csv, 'wholesaler'));
                    $this->info("Report sent to wholesaler: {$user->email}");
                }
            } catch (\Exception $e) {
                $this->error("Error sending report to {$user->email}: " . $e->getMessage());
                \Log::error("Wholesaler report error for {$user->email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Get stock status label
     */
    private function getStockStatus($inventoryItem)
    {
        if ($inventoryItem->stock_quantity <= $inventoryItem->min_stock_level) {
            return 'Low Stock';
        } elseif ($inventoryItem->stock_quantity >= $inventoryItem->max_stock_level) {
            return 'Overstocked';
        } else {
            return 'Optimal';
        }
    }

    /**
     * Generate manufacturer report
     */
    private function generateManufacturerReport($user)
    {
        try {
            if (!$user->manufacturer) {
                $this->error("User {$user->id} has no manufacturer relationship");
                return null;
            }

            $lastWeek = Carbon::now()->subWeek();
            
            $orders = Order::with(['orderItems'])
                ->where('manufacturer_id', $user->manufacturer->id)
                ->where('created_at', '>=', $lastWeek)
                ->get();

            $totalOrders = $orders->count();
            $totalAmount = $orders->sum('total_amount');
            $completedOrders = $orders->where('status', 'delivered')->count();
            $pendingOrders = $orders->where('status', 'pending')->count();

            // Create a temporary file pointer
            $fp = fopen('php://temp', 'r+');

            // Write headers for weekly summary
            fputcsv($fp, ['Weekly Order Summary']);
            fputcsv($fp, ['Period:', $lastWeek->format('Y-m-d') . ' to ' . Carbon::now()->format('Y-m-d')]);
            fputcsv($fp, ['Total Orders:', $totalOrders]);
            fputcsv($fp, ['Total Amount:', 'UGX ' . number_format($totalAmount, 2)]);
            fputcsv($fp, ['Completed Orders:', $completedOrders]);
            fputcsv($fp, ['Pending Orders:', $pendingOrders]);
            fputcsv($fp, []); // Empty line

            // Write headers for order details
            fputcsv($fp, [
                'Order #',
                'Status',
                'Total Amount',
                'Order Date',
                'Items Count',
                'Payment Status'
            ]);

            // Write order records
            foreach ($orders as $order) {
                fputcsv($fp, [
                    $order->order_number,
                    ucfirst($order->status),
                    'UGX ' . number_format($order->total_amount, 2),
                    $order->created_at->format('Y-m-d H:i'),
                    $order->orderItems->count(),
                    ucfirst($order->payment_status ?? 'Pending')
                ]);
            }

            // Get the content
            rewind($fp);
            $content = stream_get_contents($fp);
            fclose($fp);
            
            return $content;
        } catch (\Exception $e) {
            $this->error("Error generating manufacturer report: " . $e->getMessage());
            \Log::error("Error generating manufacturer report: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate wholesaler report
     */
    private function generateWholesalerReport($user)
    {
        try {
            if (!$user->wholesaler) {
                $this->error("User {$user->id} has no wholesaler relationship");
                return null;
            }

            $lastWeek = Carbon::now()->subWeek();
            
            $orders = Order::with(['orderItems', 'manufacturer'])
                ->where('wholesaler_id', $user->wholesaler->id)
                ->where('created_at', '>=', $lastWeek)
                ->get();

            $totalOrders = $orders->count();
            $totalAmount = $orders->sum('total_amount');
            $avgOrderValue = $totalOrders > 0 ? $totalAmount / $totalOrders : 0;

            // Create a temporary file pointer
            $fp = fopen('php://temp', 'r+');

            // Write headers
            fputcsv($fp, [
                'Order #',
                'Status',
                'Total Amount',
                'Items Count',
                'Manufacturer',
                'Order Date',
                'Delivery Date',
                'Payment Status'
            ]);

            // Write order records
            foreach ($orders as $order) {
                fputcsv($fp, [
                    $order->order_number,
                    $order->status,
                    number_format($order->total_amount, 2),
                    $order->orderItems->count(),
                    $order->manufacturer ? $order->manufacturer->name : 'N/A',
                    $order->created_at->format('Y-m-d H:i'),
                    $order->delivery_date ? Carbon::parse($order->delivery_date)->format('Y-m-d') : 'Pending',
                    $order->payment_status ?? 'Pending'
                ]);
            }

            // Add summary section
            fputcsv($fp, []); // Empty line
            fputcsv($fp, ['Weekly Summary']);
            fputcsv($fp, ['Total Orders:', $totalOrders]);
            fputcsv($fp, ['Total Amount:', number_format($totalAmount, 2)]);
            fputcsv($fp, ['Average Order Value:', number_format($avgOrderValue, 2)]);
            fputcsv($fp, ['Period:', $lastWeek->format('Y-m-d') . ' to ' . Carbon::now()->format('Y-m-d')]);

            // Get the content
            rewind($fp);
            $content = stream_get_contents($fp);
            fclose($fp);
            
            return $content;
        } catch (\Exception $e) {
            $this->error("Error generating wholesaler report: " . $e->getMessage());
            \Log::error("Error generating wholesaler report: " . $e->getMessage());
            return null;
        }
    }
}
