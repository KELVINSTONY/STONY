<!-- resources/views/prescription_items/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Prescription Items</h1>
    <a href="{{ route('prescription_items.create') }}" class="btn btn-primary mb-3">Add New Prescription Item</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Prescription</th>
                <th>Medicine</th>
                <th>Quantity</th>
                <th>Dosage Instructions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prescriptionItems as $prescriptionItem)
                <tr>
                    <td>{{ $prescriptionItem->prescription->id }}</td>
                    <td>{{ $prescriptionItem->medicine->name }}</td>
                    <td>{{ $prescriptionItem->quantity }}</td>
                    <td>{{ $prescriptionItem->dosage_instructions }}</td>
                    <td>
                        <a href="{{ route('prescription_items.show', $prescriptionItem->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('prescription_items.edit', $prescriptionItem->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('prescription_items.destroy', $prescriptionItem->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this prescription item?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
