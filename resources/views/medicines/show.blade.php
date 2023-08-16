@extends('layouts.master')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('medicines.index') }}">Medicines</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $medicine->name }}</li>
            </ol>
        </nav>
        <h1>Medicine Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $medicine->name }}</h5>
                <p class="card-text"><strong>Generic Name:</strong> {{ $medicine->generic_name }}</p>
                <p class="card-text"><strong>Brand Name:</strong> {{ $medicine->brand_name }}</p>
                <p class="card-text"><strong>Certificate No:</strong> {{ $medicine->certificate_number }}</p>
                <p class="card-text"><strong>Classification:</strong> {{ $medicine->classification }}</p>
                <p class="card-text"><strong>Manufacturer</strong> {{ $medicine->manufacturer }}</p>
                <p class="card-text"><strong>Product strenght:</strong> {{ $medicine->product_strenght }}</p>
                <p class="card-text"><strong>Dosage Form:</strong> {{ $medicine->dosage_form }}</p>
                {{-- <p class="card-text"><strong>Expiration Date:</strong> {{ $medicine->expiration_date }}</p> --}}
                <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
@endsection
