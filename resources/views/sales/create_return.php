@extends('layouts.master')

@section('content')

<h2>Return Items for Sale ID: {{ $sale->id }}</h2>

<form action="{{ route('returns.store') }}" method="post">
    @csrf

    <input type="hidden" name="sale_id" value="{{ $sale->id }}">

    <table>
        <thead>
            <tr>
                <th>Medicine Generic Name</th>
                <th>Sold Quantity</th>
                <th>Return Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->saleDetails as $detail)
            <tr>
                <td>{{ $detail->medicine->generic_name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>
                    <input type="number" name="return_quantity[{{ $detail->id }}]" min="0" max="{{ $detail->quantity }}">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button type="submit">Submit Return</button>
</form>

@endsection
