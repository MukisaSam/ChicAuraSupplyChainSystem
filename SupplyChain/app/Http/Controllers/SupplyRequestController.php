<?php

namespace App\Http\Controllers;

use App\Models\SupplyRequest;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplyRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Only fetch raw materials
        $rawMaterials = Item::where('type', 'raw_material')->get();
        $suppliers = Supplier::with('user')->get();
        return view('manufacturer.Orders.create-supply-request', compact('rawMaterials', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'due_date' => 'required|date|after:today',
            // Add other fields as needed
        ]);

        // Optionally, check that the item is a raw material
        $item = Item::findOrFail($request->item_id);
        if ($item->type !== 'raw_material') {
            return back()->withErrors(['item_id' => 'Selected item is not a raw material.']);
        }

        SupplyRequest::create([
            'supplier_id' => $request->supplier_id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'due_date' => $request->due_date,
            // Add other fields as needed
        ]);

        return redirect()->route('manufacturer.orders')->with('success', 'Supply request created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
