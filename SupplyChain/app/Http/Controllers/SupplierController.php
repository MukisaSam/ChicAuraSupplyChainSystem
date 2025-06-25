<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplyRequest;
use App\Models\SuppliedItem;
use App\Models\PriceNegotiation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {

        $supplier = Auth::user()->supplier;

        if (!$supplier) {
        abort(403, 'You are not a supplier.');
    }
        $supplyRequests = $supplier->supplyRequests()->with('item')->latest()->get();
        $suppliedItems = $supplier->suppliedItems()->with('item')->latest()->get();

        return view('supplier.dashboard', compact('supplier', 'supplyRequests', 'suppliedItems'));
    }

    public function dashboard()
    {
        $supplier = Auth::user()->supplier;

        if (!$supplier) {
            abort(403, 'You are not a supplier.');
        }

        $supplyRequests = $supplier->supplyRequests()->with('item')->latest()->get();
        $suppliedItems = $supplier->suppliedItems()->with('item')->latest()->get();
        $items = \App\Models\Item::where('is_active', true)->get();
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
        return view('supplier.dashboard', compact('supplier', 'supplyRequests', 'suppliedItems', 'items', 'paymentTypes', 'deliveryMethods', 'stats', 'supplyTrends'));
    }

    public function showSupplyRequest(SupplyRequest $supplyRequest)
    {
        $this->authorize('view', $supplyRequest);
        return view('supplier.supply-request.show', compact('supplyRequest'));
    }

    public function updateSupplyRequest(Request $request, SupplyRequest $supplyRequest)
    {
        $this->authorize('update', $supplyRequest);

        $validated = $request->validate([
            'status' => 'required|in:accepted,declined',
            'notes' => 'nullable|string',
        ]);

        $supplyRequest->update($validated);

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

        $stats = [
            'total_supplied' => $supplier->suppliedItems()->sum('delivered_quantity'),
            'average_rating' => $supplier->suppliedItems()->avg('quality_rating'),
            'active_requests' => $supplier->supplyRequests()->where('status', 'pending')->count(),
            'total_revenue' => $supplier->suppliedItems()->sum(DB::raw('price * delivered_quantity')),
        ];

        $supplyTrends = $supplier->suppliedItems()
            ->selectRaw('MONTH(delivery_date) as month, SUM(delivered_quantity) as total')
            ->groupBy('month')
            ->get();

        return view('supplier.analytics', compact('stats', 'supplyTrends'));
    }

    public function chat()
    {
        $supplier = Auth::user()->supplier;

        if (!$supplier) {
            abort(403, 'You are not a supplier.');
        }

        return view('supplier.chat');
    }

    public function reports()
    {
        $supplier = Auth::user()->supplier;

        if (!$supplier) {
            abort(403, 'You are not a supplier.');
        }

        return view('supplier.reports');
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
}
