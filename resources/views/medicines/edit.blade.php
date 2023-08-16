@extends('layouts.master')
@section('content')
    <div class="container">
        <h1>Edit Medicine</h1>
        <form method="POST" action="{{ route('medicines.update', $medicine) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="brand_name">Brand Name</label>
                <input type="text" class="form-control" id="brand_name" brand_name="brand_name" value="{{ $medicine->brand_name }}">
            </div>
            <div class="form-group">
                <label for="dosage_form">Dosage Form</label>
                <textarea class="form-control" id="dosage_form" name="dosage_form">{{ $medicine->dosage_form }}</textarea>
            </div>
            <div class="form-group">
                <label for="generic_name">Generic Name</label>
                <input type="text" class="form-control" id="generic_name" name="generic_name" value="{{ $medicine->generic_name }}">
            </div>
            <div class="form-group">
                <label for="active_ingredients">Ingredients</label>
                <input type="text" class="form-control" id="active_ingredients" name="active_ingredients" value="{{ $medicine->active_ingredients }}">
            </div>
            <div class="form-group">
                <label for="manufacturer">Manufacturer</label>
                <input type="text" class="form-control" id="manufacturer" name="manufacturer" value="{{ $medicine->manufacturer }}">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
@endsection
