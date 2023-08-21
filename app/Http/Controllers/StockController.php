<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoodsReceiving;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MedicinesExport;


class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('goods_received')->select('goods_received.*','medicines.*')->join('medicines','medicines.id','=','goods_received.medicine_id')
        ->get();
        return view('goods_received.index')->with(['data'=> $data]);
    }

    public function sale (){
        $data = DB::table('inventory')->select('inventory.*','medicines.*')
            ->join('medicines','medicines.id','=','inventory.medicine_id')
            ->where('inventory.quantity','>',1)
        ->get();
        return view('sales.create')->with(['data'=> $data]);
    }

    public function exportToExcel()
{
    return Excel::download(new MedicinesExport(), 'medicines.xlsx');
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
        //
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
