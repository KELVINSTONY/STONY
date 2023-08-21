{{-- @extends('layouts.master')

@section('content')
<table>
    <thead>
        <tr>
            <th>Id</th>
            <th> Name</th>
            <th> Quantity</th>
            <th>user</th>
            <!-- Add more headers if needed -->
        </tr>
    </thead>
    <tbody>
        @foreach ($sold_items as $sale)
        <tr>
            @foreach ($sale->saleDetails as $detail)
                <td> {{ $detail->sale_id }}</td>
                <td> {{ $detail->medicine->generic_name }}</td>
                <td> {{ $detail->quantity }}</td>
                <td> {{ $sale->user->name }}</td>
             @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
@endsection --}}
@extends('layouts.master')

@section('content')
    <h2>Return Sold Items</h2>

    <form action="{{ route('return-store') }}" method="post">
        @csrf
        <table>
            <thead>
            <tr>
                <th>Sale ID</th>
                <th>Sale Detail ID</th>
                <th>Medicine ID</th>
                <th>Dosage Form</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
                <th>Return Quantity</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sold_items as $sale)
                @foreach($sale->saleDetails as $salesDetail)
                    <tr>
                        <td>{{ $salesDetail->sale_id }}</td>
                        <td>{{ $salesDetail->id }}</td>
                        <td>{{ $salesDetail->medicine_id }}</td>
                        <td>{{ $salesDetail->medicine->generic_name }}</td>
                        <td>{{ $salesDetail->quantity }}</td>
                        <td>{{ $salesDetail->unit_price }}</td>
                        <td>{{ $salesDetail->subtotal }}</td>
                        <td>
                            <input type="number" name="return_quantity[{{ $salesDetail->sale_id }}][{{ $salesDetail->id }}]" min="0" max="{{ $salesDetail->quantity }}">
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
        <button type="submit">Submit Return</button>
    </form>
@endsection


{{--    @foreach ($sold_items as $detail)--}}
{{--    <table>--}}
{{--        <thead>--}}
{{--            <tr>--}}
{{--                <th>Medicine Generic Name</th>--}}
{{--                <th>Sold Quantity</th>--}}
{{--                <th>Return Quantity</th>--}}
{{--            </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--            <input type="hidden" name="sale_id" value="{{ $detail->sale_id }}">--}}
{{--            <tr>--}}

{{--                <td>{{ $detail->medicine->id }}</td>--}}
{{--                <td>{{ $detail->quantity }}</td>--}}
{{--                <td>--}}
{{--                    <input type="number" name="return_quantity[{{ $detail->id }}]" min="0" max="{{ $detail->quantity }}">--}}
{{--                </td>--}}
{{--            </tr>--}}

{{--        </tbody>--}}
{{--    </table>--}}
{{--    @endforeach--}}
{{--    <button type="submit">Submit Return</button>--}}
{{--</form>--}}

{{--@endsection--}}

