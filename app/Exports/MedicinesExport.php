<?php

namespace App\Exports;

use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;

class MedicinesExport implements FromQuery
{
    public function query()
    {
        return DB::table('goods_received')
            ->select('goods_received.*', 'medicines.*')
            ->join('medicines', 'medicines.id', '=', 'goods_received.medicine_id')->orderBy('medicines.id');
    }

    public function headings(): array
    { 
        $columns = $this->getColumnsFromQuery();
        return $columns;
    }

    private function getColumnsFromQuery(): array
    {
        $query = $this->query();
        $columns = array_keys($query->first()); // Get column names from the first row
        // You might want to modify the column names here if needed

        return $columns;
    }
}