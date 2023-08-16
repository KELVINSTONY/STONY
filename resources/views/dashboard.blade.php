<!-- resources/views/dashboard.blade.php -->
@extends('layouts.master')

@section('content')
<div class="col-sm-12">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @can('delete-users')
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#pills-patient" role="tab"
                   aria-selected="true">Patients
                    Summary</a>
            </li>
            @endcan 
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pills-consultation" role="tab"
                   aria-selected="false">Consultations
                    Summary</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pills-laboratory" role="tab"
                   aria-selected="false">Laboratory Summary</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pills-procedure" role="tab" aria-selected="false">Procedures
                    Summary</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pills-pharmacy" role="tab" aria-selected="false">Pharmacy
                    Summary</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pills-billing" role="tab" aria-selected="false">Billings
                    Summary</a>
            </li>
    </ul>
    <div class="row"> 
        <div class="col-md-3">
                    <a href="{{ route('medicines.create') }}" class="btn btn-primary">Add New Medicine</a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('goods-received.create') }}" class="btn btn-primary">Receive goods</a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('patients.create') }}" class="btn btn-primary">Add New Patient</a>
       </div>
       <div class="col-md-3">
        {{-- <a href="{{ route('roles.create') }}" class="btn btn-primary">Add New Role</a> --}}
        <a href="{{ route('permissions.index') }}" class="btn btn-primary">Add New Role</a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('prescriptions.create') }}" class="btn btn-primary">Add New Prescription</a>
        </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Brand Name</th>
                        <th>Generic Name</th>
                        <th>Product Strength</th>
                        <th>Dosage Form</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $medicine)
                        <tr>
                            <td>{{ $medicine->brand_name }}</td>
                            <td>{{ $medicine->generic_name }}</td>
                            <td>{{ $medicine->product_strenght }}</td>
                            <td>{{ $medicine->dosage_form }}</td>
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
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item {{ $medicines->currentPage() == 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $medicines->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    @if($medicines->currentPage() > 3)
                        <li class="page-item"><a class="page-link" href="{{ $medicines->url(1) }}">1</a></li>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                    @for($i = max(1, $medicines->currentPage() - 2); $i <= min($medicines->lastPage(), $medicines->currentPage() + 2); $i++)
                        <li class="page-item {{ $medicines->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $medicines->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    @if($medicines->currentPage() < $medicines->lastPage() - 2)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        <li class="page-item"><a class="page-link" href="{{ $medicines->url($medicines->lastPage()) }}">{{ $medicines->lastPage() }}</a></li>
                    @endif
                    <li class="page-item {{ $medicines->currentPage() == $medicines->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $medicines->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
            @isset($patients)
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
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item {{ $patients->currentPage() == 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $patients->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    @if($patients->currentPage() > 3)
                        <li class="page-item"><a class="page-link" href="{{ $patients->url(1) }}">1</a></li>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                    @for($i = max(1, $patients->currentPage() - 2); $i <= min($patients->lastPage(), $patients->currentPage() + 2); $i++)
                        <li class="page-item {{ $patients->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $patients->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    @if($patients->currentPage() < $patients->lastPage() - 2)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        <li class="page-item"><a class="page-link" href="{{ $patients->url($patients->lastPage()) }}">{{ $patients->lastPage() }}</a></li>
                    @endif
                    <li class="page-item {{ $patients->currentPage() == $patients->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $patients->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        @endisset
        
    </div>
    <div class="row">
            @isset($prescriptions)
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
        @endisset
            @isset($prescriptionItems)
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
        @endisset
    </div>
    <div class="row">
            @isset($roles)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this role?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endisset
    </div>
</div>
@endsection
