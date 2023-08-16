@extends('layouts.master')

@section('content')

<form method="POST" action="{{ route('patients.store') }}">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputFirstName">First Name</label>
            <input type="text" class="form-control" id="inputFirstName" name="first_name" placeholder="first name">
        </div>
        <div class="form-group col-md-6">
            <label for="inputLastName">Last Name</label>
            <input type="text" class="form-control" id="inputLastName" name="last_name" placeholder="last name">
        </div>
    </div>
    <div class="form-group">
        <div class="form-group col-md-4">
            <label for="inputDob">Date of Birth</label>
            <input type="date" class="form-control" id="inputDob" name="date_of_birth" placeholder="D.O.B">
        </div>
        <div class="form-group">
            <div class="form-group col-md-2">
                <label for="inputAddress2">Gender</label>
                <label>
                    <input type="radio" name="gender" value="male"> Male
                </label>
                <label>
                    <input type="radio" name="gender" value="female"> Female
                </label>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputContact">Contact</label>
                    <input type="phone" class="form-control" name="contact_number" id="inputContact">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputZip">Email</label>
                    <input type="email" class="form-control" name="email" id="inputEmail">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">register</button>
</form>

@endsection