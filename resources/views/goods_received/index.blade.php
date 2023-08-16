@extends('layouts.master')

@section('content')
    <h1>Current stock</h1>
    <a href="{{ route('goods-received.create') }}" class="btn btn-primary mb-3">receive goods</a>
    <a href="{{ route('export.medicines') }}" class="btn btn-success mb-3">Export to Excel</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Generic Name</th>
                <th>Brand Name</th>
                <th>Product Strength</th>
                <th>quantity</th>
                <th>Unit Cost</th>
                <th>Total Cost</th>
                <th>Selling Price</th>
                <th>Total Profit</th>
                <th>Expiry Date</th>
            </tr>
        </thead>
        <tbody>
           
            @php
                $totalMedicines = 0;
                $totalCosts = 0;
                $totalProfits = 0;
            @endphp
            
            @foreach($data as $medicine)
                @php
                    $total_cost = ($medicine->quantity * $medicine->unit_cost);
                    $total_profit = (($medicine->quantity * $medicine->selling_price) - $total_cost);
                    
                    $totalMedicines += $medicine->quantity;
                    $totalCosts += $total_cost;
                    $totalProfits += $total_profit;
                @endphp
                <tr>
                    <td>{{ $medicine->generic_name }}</td>
                    <td>{{ $medicine->brand_name }}</td>
                    <td>{{ $medicine->product_strenght }}</td>
                    <td>{{ $medicine->quantity }}</td>
                    <td>{{ $medicine->unit_cost }}</td>
                    <td>{{ $total_cost }}</td>
                    <td>{{ $medicine->selling_price }}</td>
                    <td>{{ $total_profit }}</td>
                    <td>{{ $medicine->expire_date }}</td>
                </tr>
            @endforeach
            
            <tr>
                <td colspan="3"><strong>Total:</strong></td>
                <td>{{ $totalMedicines }}</td>
                <td></td>
                <td>{{ $totalCosts }}</td>
                <td></td>
                <td>{{ $totalProfits }}</td>
                <td></td>
            </tr>
            
        </tbody>
    </table>
@endsection
