<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Order;
use App\Models\Item;
use App\Models\Supplier;
use App\Mail\WeeklyReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use Carbon\Carbon;

class SendWeeklyReports extends Command
{
    protected $signature = 'reports:send-weekly';
    protected $description = 'Generate and email weekly reports as CSV attachments to manufacturers, suppliers and wholesalers.';

    public function handle()
    {
        $this->sendManufacturerReports();
        $this->sendWholesalerReports();
        $this->sendSupplierReports();
        $this->info('Weekly reports sent to manufacturers and wholesalers.');
        return 0;
    }

    private function sendManufacturerReports()
    {
        $manufacturers = User::where('role', 'manufacturer')->get();
        foreach ($manufacturers as $user) {
            $csv = $this->generateManufacturerReport($user);
            Mail::to($user->email)->send(new WeeklyReportMail($user, $csv, 'manufacturer'));
        }
    }

    private function sendWholesalerReports()
    {
        $wholesalers = User::where('role', 'wholesaler')->get();
        foreach ($wholesalers as $user) {
            $csv = $this->generateWholesalerReport($user);
            Mail::to($user->email)->send(new WeeklyReportMail($user, $csv, 'wholesaler'));
        }
    }
    private function sendSupplierReports()
    {
        $supplier = User::where('role', 'supplier')->get();
        foreach ($suppler as $user) {
            $csv = $this->generateSupplierReport($user);
            Mail::to($user->email)->send(new WeeklyReportMail($user, $csv, 'supplier'));
        }
    }

    private function generateManufacturerReport($user)
    {
        $orders = Order::where('manufacturer_id', $user->manufacturer->id ?? null)
            ->where('created_at', '>=', Carbon::now()->subWeek())
            ->get();
        $items = Item::where('is_active', true)->get();
        $header = ['Order #', 'Status', 'Total Amount', 'Order Date'];
        $records = $orders->map(function($order) {
            return [
                $order->order_number,
                $order->status,
                $order->total_amount,
                $order->order_date,
            ];
        })->toArray();
        $csv = Writer::createFromString('');
        $csv->insertOne($header);
        $csv->insertAll($records);
        return $csv->getContent();
    }

    private function generateWholesalerReport($user)
    {
        $orders = Order::where('wholesaler_id', $user->wholesaler->id ?? null)
            ->where('created_at', '>=', Carbon::now()->subWeek())
            ->get();
        $header = ['Order #', 'Status', 'Total Amount', 'Order Date'];
        $records = $orders->map(function($order) {
            return [
                $order->order_number,
                $order->status,
                $order->total_amount,
                $order->order_date,
            ];
        })->toArray();
        $csv = Writer::createFromString('');
        $csv->insertOne($header);
        $csv->insertAll($records);
        return $csv->getContent();
    }
    private function generateSupplierReport($user)
    {
        $orders = Order::where('supplier_id', $user->supplier->id ?? null)
            ->where('created_at', '>=', Carbon::now()->subWeek())
            ->get();
        $header = ['Order #', 'Status', 'Total Amount', 'Order Date'];
        $records = $orders->map(function($order) {
            return [
                $order->order_number,
                $order->status,
                $order->total_amount,
                $order->order_date,
            ];
        })->toArray();
        $csv = Writer::createFromString('');
        $csv->insertOne($header);
        $csv->insertAll($records);
        return $csv->getContent();
    }
} 
