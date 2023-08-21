{{--@extends('layouts.master')--}}

{{--@section('content')--}}
{{--    <h1>Receive Goods</h1>--}}
{{--    <form action="{{ route('goods-received.store') }}" method="POST">--}}
{{--        @csrf--}}
{{--        <label for="medicine_id">Search and Select a Product:</label>--}}
{{--        <input type="text" id="searchInput" onkeyup="filterProducts()">--}}
{{--        <select name="medicine_id" id="medicine_id">--}}
{{--            <option value="">Select a product</option>--}}
{{--            @foreach($product as $products)--}}
{{--                <option value="{{ $products->id }}">{{ $products->brand_name }}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}

{{--        <label for="quantity">Quantity:</label>--}}
{{--        <input type="integer" name="quantity">--}}
{{--        <label for="unit_cost">Unit Cost:</label>--}}
{{--        <input type="integer" name="unit_cost">--}}
{{--        <label for="selling_price">Selling Price:</label>--}}
{{--        <input type="integer" name="selling_price">--}}
{{--        <label for="expire_date">Expire Date:</label>--}}
{{--        <input type="date" name="expire_date">--}}
{{--        <button type="submit">Receive Goods</button>--}}
{{--    </form>--}}

{{--    <script>--}}
{{--        function filterProducts() {--}}
{{--            var input, filter, select, option, i, txtValue;--}}
{{--            input = document.getElementById("searchInput");--}}
{{--            filter = input.value.toUpperCase();--}}
{{--            select = document.getElementById("medicine_id");--}}
{{--            option = select.getElementsByTagName("option");--}}

{{--            for (i = 0; i < option.length; i++) {--}}
{{--                txtValue = option[i].textContent || option[i].innerText;--}}
{{--                if (txtValue.toUpperCase().indexOf(filter) > -1) {--}}
{{--                    option[i].style.display = "";--}}
{{--                } else {--}}
{{--                    option[i].style.display = "none";--}}
{{--                }--}}
{{--            }--}}
{{--        }--}}

{{--        // // Add an event listener to update medicine_id when an option is selected--}}
{{--        // document.getElementById("medicine_id").addEventListener("change", function() {--}}
{{--        //     var selectedProductId = this.value; // Get the selected medicine_id--}}
{{--        //     // Do something with the selectedProductId, e.g., set it as a hidden field's value--}}
{{--        //     document.getElementById("selected_medicine_id").value = selectedProductId;--}}
{{--        // });--}}
{{--    </script>--}}

{{--@endsection--}}
@extends('layouts.master')

@section('content')
    <h1>Receive Goods</h1>
    <form action="{{ route('goods-received.store') }}" method="POST">
        @csrf
        <label for="medicine_id">Search and Select a Product:</label>
        <input type="text" id="searchInput" onkeyup="filterProducts()">
        <select name="medicine_id" id="medicine_id">
            <option value="">Select a product</option>
            @foreach($product as $products)

                <option value="{{ $products->id }}">
                    {{ $products->brand_name }} - {{ $products->product_strenght }}
                </option>
            @endforeach
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity">
        <label for="unit_cost">Unit Cost:</label>
        <input type="number" name="unit_cost">
        <label for="selling_price">Selling Price:</label>
        <input type="number" name="selling_price">
        <label for="expire_date">Expire Date:</label>
        <input type="date" name="expire_date">
        <button type="submit">Receive Goods</button>
    </form>

    <script>
        function filterProducts() {
            var input, filter, select, option, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            select = document.getElementById("medicine_id");
            option = select.getElementsByTagName("option");

            for (i = 0; i < option.length; i++) {
                txtValue = option[i].textContent || option[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    option[i].style.display = "";
                } else {
                    option[i].style.display = "none";
                }
            }
        }
    </script>
@endsection
