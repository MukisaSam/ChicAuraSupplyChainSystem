<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    // List all invoices for the logged-in wholesaler
    public function index()
    {
        $user = Auth::user();
        $invoices = Invoice::whereHas('order', function($q) use ($user) {
            $q->where('wholesaler_id', $user->wholesaler->id);
        })->orderBy('created_at', 'desc')->get();
        return view('wholesaler.invoices.index', compact('invoices'));
    }

    // Show a single invoice (with authorization)
    public function show($id)
    {
        $user = Auth::user();
        $invoice = Invoice::findOrFail($id);
        // Authorization: only allow if this invoice belongs to the wholesaler
        if ($invoice->order->wholesaler_id !== $user->wholesaler->id) {
            abort(403, 'Access denied.');
        }
        return view('wholesaler.invoices.show', compact('invoice'));
    }
}
