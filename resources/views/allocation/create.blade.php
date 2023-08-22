@extends("layouts.master")

@section('page_css')
@endsection

@section('content-title')
    Allocate Patient to the Doctor

@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
@endsection



@section('content')

    <div class="col-sm-12">

        <div class="card">

            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger mx-auto" style="width: 70%">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success mx-auto" style="width: 70%">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="container">
                    <div class="row my-2">
                        <div class="col-lg-8 order-lg-2">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                     aria-labelledby="pills-home-tab">
                                    <form method="POST" action="{{ route('allocate-store') }}">
                                        @csrf

                                        <h6>Full Name</h6>
                                        <p>
                                            {{ Auth::user()->firstName }}
                                        </p>
                                        <h6>Last Name</h6>
                                        <p>
                                            {{ Auth::user()->lastName }}
                                        </p>
                                        <div class="input-group mb-3 mx-auto" style="width: 70%">
                                            <select id="doctor_id" class="form-control" name="patient_id">
                                                <option value="">Select a Patient</option>
                                                    <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                            </select>
                                            @if ($errors->has('patient_id'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('patient_id') }}</strong>
                                                     </span>
                                            @endif
                                        </div>


                                        <div class="input-group mb-3 mx-auto" style="width: 70%">
                                            <select id="user_id" class="form-control" name="user_id">
                                                <option value="">Select a Doctor</option>
                                                @foreach ($user as $doctor)
                                                    <option value="{{ $doctor->id }}">{{ $doctor->firstName }} {{ $doctor->lastName }}</option>
                                                @endforeach
                                            </select>
                                                    @if ($errors->has('user_id'))
                                                        <span class="help-block">
                                                    <strong>{{ $errors->first('user_id') }}</strong>
                                                     </span>
                                            @endif
                                        </div>


                                        <div class="form-group mx-auto">
                                            <center>
                                                <div class="col-md-12 col-md-offset-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        allocate
                                                    </button>
                                                </div>
                                            </center>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                     aria-labelledby="pills-profile-tab">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('page_scripts')
    <script>
        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var message = "Are you sure you want to delete Role '".concat(button.data('name'), "'?");
            var modal = $(this);
            modal.find('.modal-body #message').text(message);
            modal.find('.modal-body #role_id').val(id)
        })
    </script>

@endpush
