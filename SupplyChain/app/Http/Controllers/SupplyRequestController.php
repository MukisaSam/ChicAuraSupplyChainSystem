<?php

namespace App\Http\Controllers;

use App\Models\SupplyRequest;
use App\Models\Item;
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
        return view('manufacturer.Orders.create-supply-request', compact('rawMaterials'));
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
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            // Add other fields as needed
        ]);

        // Optionally, check that the item is a raw material
        $item = Item::findOrFail($request->item_id);
        if ($item->type !== 'raw_material') {
            return back()->withErrors(['item_id' => 'Selected item is not a raw material.']);
        }

        SupplyRequest::create([
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            // Add other fields as needed
        ]);

        return redirect()->route('supply-requests.index')->with('success', 'Supply request created!');
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
