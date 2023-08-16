<!-- resources/views/patients/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Patients</h1>
    <a href="{{ route('patients.create') }}" class="btn btn-primary mb-3">Add New Patient</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Contact Number</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
                <tr>
                    <td>{{ $patient->first_name }}</td>
                    <td>{{ $patient->last_name }}</td>
                    <td>{{ $patient->date_of_birth }}</td>
                    <td>{{ $patient->gender }}</td>
                    <td>{{ $patient->contact_number }}</td>
                    <td>{{ $patient->email }}</td>
                    <td>
                        <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this patient?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
