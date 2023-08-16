<!-- resources/views/roles/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Edit Role: {{ $role->name }}</h1>
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}" required>
        </div>
        <!-- Add more form fields for role details if needed -->
        <button type="submit" class="btn btn-primary">Update Role</button>
    </form>
@endsection
