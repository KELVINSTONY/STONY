<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceiving;
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
        $goods = GoodsReceiving::where('medicine_id', $request->medicine_id)->first();
    
        if ($goods) {
            $available_quantity = $goods->quantity;
            $sold_quantity = $request->quantity;
            $remaining = $available_quantity - $sold_quantity;
    
            $unit_cost = $goods->unit_cost; 
            $selling_price = $goods->selling_price;
            $new_total_cost = $remaining * $unit_cost;
            $new_total_profit = ($remaining * $selling_price) - $new_total_cost;
            $goods->quantity = $remaining;
            $goods->total_cost = $new_total_cost;
            $goods->total_profit = $new_total_profit;
            $goods->save();
    
            // Handle any other attributes you want to update
            
            // Optionally, you can also consider validation and error handling here.
            
            return Redirect::back()->with('success', 'Goods record updated successfully.');
        } else {
            // Handle the case when goods with the specified medicine_id were not found.
        }
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
