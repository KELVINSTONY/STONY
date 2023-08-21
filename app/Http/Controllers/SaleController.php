<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceiving;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\Medicine;
use App\Models\Returni;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'medicine_id' => 'required|exists:medicines,id', // Assuming 'products' is the table name for medicines
            'quantity' => 'required|integer|min:1',
        ]);
        $goods = GoodsReceiving::where('medicine_id',$validatedData['medicine_id'])->first();

        if ($goods) {
            $available_quantity = $goods->quantity;
            $sold_quantity = $validatedData['quantity'];
            $remaining = $available_quantity - $sold_quantity;

            $unit_cost = $goods->unit_cost;
            $selling_price = $goods->selling_price;
            $new_total_cost = $remaining * $unit_cost;
            $new_total_profit = ($remaining * $selling_price) - $new_total_cost;
            $goods->quantity = $remaining;
            $goods->total_cost = $new_total_cost;
            $goods->total_profit = $new_total_profit;
            $total_amount = $selling_price * $sold_quantity;
            $goods->save();
            $sale = Sale::create([
                'medicine_id' => $validatedData['medicine_id'],
                'user_id' => Auth::user()->id,
                'quantity' => $validatedData['quantity'],
                'total_amount' => $total_amount,
            ]);
            $inventory = Inventory::where('medicine_id', $sale->medicine_id)->firstOrFail();
            $inventory->quantity -= $sale->quantity;
            $inventory->save();
                SalesDetail::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $sale->medicine_id,
                    'quantity' => $sale->quantity,
                    'unit_price' => $goods->selling_price,
                    'subtotal' => $sale->total_amount,
                ]);
                return Redirect::back()->with('success', 'Goods record updated successfully.');
            } else {
                // Handle the case when goods with the specified medicine_id were not found.
            }

    }

    public function returnProcess() {
        $sales = Sale::with('saleDetails.medicine.user')->get();
        return view('sales.return')->with(['sold_items' => $sales]);
    }

    public function processReturn(Request $request)
    {
        $validatedData = $request->validate([
            'return_quantity' => 'required|array',
        ]);

        foreach ($validatedData['return_quantity'] as $saleId => $returnQuantities) {
            $sale = Sale::find($saleId);
            foreach ($returnQuantities as $saleDetailId => $returnQuantity) {
                   $salesDetail = SalesDetail::find($saleDetailId);
                if ($sale && $salesDetail) {
                    $remaining_quantity = ($salesDetail->quantity -  $returnQuantity);
                   $salesDetail->update(['quantity' => $remaining_quantity]);
                }
            }
        }
        return Redirect::back()->with('success', 'Goods record updated successfully.');
        // Redirect or return response as needed
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
