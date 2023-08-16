<!-- resources/views/prescriptions/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Prescriptions</h1>
    <a href="{{ route('prescriptions.create') }}" class="btn btn-primary mb-3">Add New Prescription</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Prescription Date</th>
                <th>Prescribing Doctor</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prescriptions as $prescription)
                <tr>
                    <td>{{ $prescription->patient->full_name }}</td>
                    <td>{{ $prescription->prescription_date }}</td>
                    <td>{{ $prescription->prescribing_doctor }}</td>
                    <td>{{ $prescription->remarks }}</td>
                    <td>
                        <a href="{{ route('prescriptions.show', $prescription->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('prescriptions.destroy', $prescription->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this prescription?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
