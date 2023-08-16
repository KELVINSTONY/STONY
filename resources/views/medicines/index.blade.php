<!-- resources/views/medicines/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Medicines</h1>
    <a href="{{ route('medicines.create') }}" class="btn btn-primary mb-3">Add New Medicine</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Unit Price</th>
                <th>Quantity in Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medicines as $medicine)
                <tr>
                    <td>{{ $medicine->name }}</td>
                    <td>{{ $medicine->description }}</td>
                    <td>{{ $medicine->unit_price }}</td>
                    <td>{{ $medicine->quantity_in_stock }}</td>
                    <td>
                        <a href="{{ route('medicines.show', $medicine->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this medicine?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
