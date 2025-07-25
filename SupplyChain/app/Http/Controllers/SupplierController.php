<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplyRequest;
use App\Models\SuppliedItem;
use App\Models\PriceNegotiation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;

class SupplierController extends Controller
{
    public function index()
    {

        $supplier = Auth::user()->supplier;

        if (!$supplier) {
        abort(403, 'You are not a supplier.');
    }
        $supplyRequests = $supplier->supplyRequests()->with('item')->latest()->paginate(10);
        $suppliedItems = $supplier->suppliedItems()->with('item')->latest()->paginate(10);

        return view('supplier.dashboard', compact('supplier', 'supplyRequests', 'suppliedItems'));
    }

    public function dashboard()
    {
        $supplier = Auth::user()->supplier;

        if (!$supplier) {
            abort(403, 'You are not a supplier.');
        }

        $supplyRequests = $supplier->supplyRequests()->with('item')->latest()->paginate(10);
        $suppliedItems = $supplier->suppliedItems()->with('item')->latest()->paginate(10);
        $items = \App\Models\Item::where('is_active', true)->where('type', 'raw_material')->get();
        $paymentTypes = ['cash', 'credit', 'bank_transfer'];
        $deliveryMethods = ['pickup', 'delivery'];
        $stats = [
            'total_supplied' => $supplier->suppliedItems()->sum('delivered_quantity'),
            'average_rating' => $supplier->suppliedItems()->avg('quality_rating'),
            'active_requests' => $supplier->supplyRequests()->where('status', 'pending')->count(),
            'total_revenue' => $supplier->suppliedItems()->sum(\DB::raw('price * delivered_quantity')),
        ];
        $supplyTrends = $supplier->suppliedItems()
            ->selectRaw('MONTH(delivery_date) as month, SUM(delivered_quantity) as total')
            ->groupBy('month')
            ->get();

        // Monthly report: group by month, sum quantity, revenue, avg rating
        $monthlyReport = $supplier->suppliedItems()
            ->selectRaw('MONTH(delivery_date) as month, SUM(delivered_quantity) as quantity, SUM(price * delivered_quantity) as revenue, AVG(quality_rating) as avg_rating')
            ->groupBy('month')
            ->get();

        // Item report: group by item, sum quantity, avg price, avg rating
        $itemReport = $supplier->suppliedItems()
            ->selectRaw('item_id, SUM(delivered_quantity) as total_quantity, AVG(price) as avg_price, AVG(quality_rating) as avg_rating')
            ->with(['item' => function($query) { $query->where('type', 'raw_material'); }])
            ->groupBy('item_id')
            ->get();

        // Fetch chat contacts
        $user = Auth::user();
        $admins = \App\Models\User::where('role', 'admin')->get();
        $manufacturers = \App\Models\User::where('role', 'manufacturer')->get();
        $wholesalers = \App\Models\User::where('role', 'wholesaler')->get();

        // Example unread counts (replace with your actual logic if needed)
        $unreadCounts = [];

        return view('supplier.dashboard', compact(
            'supplier', 'supplyRequests', 'suppliedItems', 'items', 'paymentTypes', 'deliveryMethods', 'stats', 'supplyTrends',
            'monthlyReport', 'itemReport', 'user', 'admins', 'manufacturers', 'wholesalers', 'unreadCounts'
        ));
    }

    public function showSupplyRequest(Request $request, SupplyRequest $supplyRequest)
    {
        $this->authorize('view', $supplyRequest);
        return view('supplier.supply-requests.show', compact('supplyRequest'));
    }

    public function updateSupplyRequest(Request $request, SupplyRequest $supplyRequest)
    {
        $this->authorize('update', $supplyRequest);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,in_progress,completed,accepted,declined',
            'notes' => 'nullable|string',
        ]);

        $supplyRequest->update($validated);

        // Create SuppliedItem if status is completed and not already created
        if ($supplyRequest->status === 'completed') {
            $existing = \App\Models\SuppliedItem::where('supply_request_id', $supplyRequest->id)->first();
            if (!$existing) {
                \App\Models\SuppliedItem::create([
                    'supplier_id' => $supplyRequest->supplier_id,
                    'supply_request_id' => $supplyRequest->id,
                    'item_id' => $supplyRequest->item_id,
                    'delivered_quantity' => $supplyRequest->quantity,
                    'delivery_date' => now(),
                    'status' => 'delivered',
                    'price' => $supplyRequest->item->base_price ?? 0,
                ]);
            }
        }

        if ($validated['status'] === 'accepted') {
            // Create price negotiation
            PriceNegotiation::create([
                'supply_request_id' => $supplyRequest->id,
                'initial_price' => $supplyRequest->item->base_price,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('supplier.supply-requests.show', $supplyRequest)
            ->with('success', 'Supply request updated successfully');
    }

    public function submitPriceNegotiation(Request $request, SupplyRequest $supplyRequest)
    {
        $this->authorize('update', $supplyRequest);

        $validated = $request->validate([
            'counter_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $negotiation = $supplyRequest->priceNegotiation;
        $negotiation->update([
            'counter_price' => $validated['counter_price'],
            'notes' => $validated['notes'],
            'status' => 'counter_offered',
        ]);

        return redirect()->route('supplier.supply-requests.show', $supplyRequest)
            ->with('success', 'Price negotiation submitted successfully');
    }

    public function updateSuppliedItem(Request $request, SuppliedItem $suppliedItem)
    {
        $this->authorize('update', $suppliedItem);

        $validated = $request->validate([
            'delivered_quantity' => 'required|numeric|min:0',
            'delivery_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $suppliedItem->update($validated);

        return redirect()->route('supplier.supplied-items.show', $suppliedItem)
            ->with('success', 'Supplied item updated successfully');
    }

    public function showSuppliedItem(SuppliedItem $suppliedItem)
    {
        $this->authorize('view', $suppliedItem);
        return view('supplier.supplied-items.show', compact('suppliedItem'));
    }

    public function analytics()
{
    $supplier = Auth::user()->supplier;
    
    // Basic stats
    $stats = [
        'total_supplied' => $supplier->suppliedItems()->sum('delivered_quantity'),
        'average_rating' => $supplier->suppliedItems()->avg('quality_rating') ?? 0,
        'active_requests' => $supplier->supplyRequests()->where('status', 'pending')->count(),
        'total_revenue' => $supplier->suppliedItems()->sum(DB::raw('price * delivered_quantity')),
    ];

    // Get data for the last 12 months
    $startDate = now()->subMonths(11)->startOfMonth();
    $endDate = now()->endOfMonth();

    // Initialize all months with 0 values
    $allMonths = collect();
    for ($i = 0; $i < 12; $i++) {
        $date = $startDate->copy()->addMonths($i);
        $allMonths->push([
            'month' => $date->month,
            'month_name' => $date->format('F'),
            'year' => $date->year,
            'total' => 0,
            'revenue' => 0,
            'avg_rating' => 0
        ]);
    }

    // Get supply trends data
    $supplyTrends = $supplier->suppliedItems()
        ->selectRaw('
            MONTH(delivery_date) as month, 
            YEAR(delivery_date) as year,
            SUM(delivered_quantity) as total,
            SUM(price * delivered_quantity) as revenue,
            AVG(quality_rating) as avg_rating
        ')
        ->whereBetween('delivery_date', [$startDate, $endDate])
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

    // Merge actual data with all months
    $mergedData = $allMonths->map(function ($month) use ($supplyTrends) {
        $found = $supplyTrends->first(function ($item) use ($month) {
            return $item->month == $month['month'] && $item->year == $month['year'];
        });
        
        if ($found) {
            $month['total'] = $found->total;
            $month['revenue'] = $found->revenue;
            $month['avg_rating'] = round($found->avg_rating, 2);
        }
        
        return $month;
    });

    // Prepare data for charts
    $chartData = [
        'months' => $mergedData->pluck('month_name'),
        'totals' => $mergedData->pluck('total'),
        'revenues' => $mergedData->pluck('revenue'),
        'ratings' => $mergedData->pluck('avg_rating'),
    ];

    // Get top 5 items by quantity
    $topItems = $supplier->suppliedItems()
        ->selectRaw('item_id, SUM(delivered_quantity) as total_quantity')
        ->with('item')
        ->groupBy('item_id')
        ->orderByDesc('total_quantity')
        ->take(5)
        ->get();

    return view('supplier.analytics.index', compact(
        'stats',
        'chartData',
        'mergedData',
        'topItems'
    ));
}
    

    public function chat()
    {
        $supplier = Auth::user()->supplier;

        if (!$supplier) {
            abort(403, 'You are not a supplier.');
        }

        return view('supplier.chat');
    }

    public function reports(Request $request)
    {
        $supplier = Auth::user()->supplier;

        if (!$supplier) {
            abort(403, 'You are not a supplier.');
        }

        // Monthly report: group by month, sum quantity, revenue, avg rating
        $monthlyReport = $supplier->suppliedItems()
            ->selectRaw('MONTH(delivery_date) as month, SUM(delivered_quantity) as quantity, SUM(price * delivered_quantity) as revenue, AVG(quality_rating) as avg_rating')
            ->groupBy('month')
            ->get();

        // Item report: group by item, sum quantity, avg price, avg rating
        $itemReport = $supplier->suppliedItems()
            ->selectRaw('item_id, SUM(delivered_quantity) as total_quantity, AVG(price) as avg_price, AVG(quality_rating) as avg_rating')
            ->with('item')
            ->groupBy('item_id')
            ->get();

        return view('supplier.reports.index', compact('monthlyReport', 'itemReport'));
    }

    // Create a new supply request (AJAX)
    public function store(Request $request)
    {
        $supplier = Auth::user()->supplier;
        if (!$supplier) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'due_date' => 'required|date|after:today',
            'payment_type' => 'required|string',
            'delivery_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        $validated['supplier_id'] = $supplier->id;
        $validated['status'] = 'pending';
        $supplyRequest = SupplyRequest::create($validated);
        $supplyRequest->load('item');
        return response()->json(['success' => true, 'supplyRequest' => $supplyRequest]);
    }

    // Delete a supply request (AJAX)
    public function destroy(SupplyRequest $supplyRequest)
    {
        $this->authorize('delete', $supplyRequest);
        $supplyRequest->delete();
        return response()->json(['success' => true]);
    }

    public function supplyRequestsIndex(Request $request)
    {
        $supplier = Auth::user()->supplier;
        $query = $supplier->supplyRequests()->with(['item' => function($query) { $query->where('type', 'raw_material'); }])->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }
        $supplyRequests = $query->paginate(10);
        return view('supplier.supply-requests.index', compact('supplyRequests'));
    }

    public function suppliedItems(Request $request)
    {
        $supplier = Auth::user()->supplier;
        if (!$supplier) {
            abort(403, 'You are not a supplier.');
        }
        $query = $supplier->suppliedItems()->with('item')->latest();
        if ($request->filled('delivery_date')) {
            $query->whereDate('delivery_date', $request->delivery_date);
        }
        $suppliedItems = $query->paginate(10);
        return view('supplier.supplied-items.index', compact('suppliedItems'));
    }

    /**
     * Handle AJAX status update for a supply request (approve, reject, change status).
     */
    public function ajaxUpdateSupplyRequestStatus(Request $request, $supplyRequestId)
    {
        $user = Auth::user();
        $supplier = $user->supplier;
        if (!$supplier) {
            return back()->withErrors(['Not a supplier']);
        }
        $supplyRequest = SupplyRequest::where('id', $supplyRequestId)
            ->where('supplier_id', $supplier->id)
            ->first();
        if (!$supplyRequest) {
            return back()->withErrors(['Supply request not found']);
        }
        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected,in_progress,completed',
        ]);
        $supplyRequest->status = $validated['status'];
        $supplyRequest->save();

        // Create SuppliedItem if status is completed and not already created
        if ($supplyRequest->status === 'completed') {
            $existing = \App\Models\SuppliedItem::where('supply_request_id', $supplyRequest->id)->first();
            if (!$existing) {
                \App\Models\SuppliedItem::create([
                    'supplier_id' => $supplyRequest->supplier_id,
                    'supply_request_id' => $supplyRequest->id,
                    'item_id' => $supplyRequest->item_id,
                    'delivered_quantity' => $supplyRequest->quantity,
                    'delivery_date' => now(),
                    'status' => 'delivered',
                    'price' => $supplyRequest->item->base_price ?? 0,
                ]);
            }
        }

        // If AJAX, return JSON. If not, redirect back.
        if ($request->ajax()) {
            return response()->json(['success' => true, 'status' => $supplyRequest->status]);
        } else {
            return redirect()->back()->with('success', 'Status updated successfully!');
        }
    }
}
