@extends('layouts.master')

@section('content')
    <h1>Receive Goods</h1>
    <form action="{{ route('sale-store') }}" method="POST">
        @csrf
        <label for="medicine_id">Search and Select a Product:</label>
        <input type="text" id="searchInput" onkeyup="filterProducts()">
        <select name="medicine_id" id="medicine_id">
            <option value="">Select a product</option>
            @foreach($data as $products)
                <option value="{{ $products->id }}">{{ $products->generic_name }}</option>
            @endforeach
        </select>

        <label for="quantity">Quantity:</label>
        <input type="integer" name="quantity">
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
    
        // // Add an event listener to update medicine_id when an option is selected
        // document.getElementById("medicine_id").addEventListener("change", function() {
        //     var selectedProductId = this.value; // Get the selected medicine_id
        //     // Do something with the selectedProductId, e.g., set it as a hidden field's value
        //     document.getElementById("selected_medicine_id").value = selectedProductId;
        // });
    </script>
    
@endsection
