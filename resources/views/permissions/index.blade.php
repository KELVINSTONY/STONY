
@extends('layouts.master')

@section('content')
@foreach ($users as $user)
    <h3>{{ $user->name }}</h3>
    <form action="{{ route('permissions.update', $user) }}" method="post">
        @csrf
        @foreach ($permissions as $permission)
            <label>
                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                       {{ $user->hasPermissionTo($permission) ? 'checked' : '' }}>
                {{ $permission->name }}
            </label><br>
        @endforeach
        <button type="submit">Save Permissions</button>
    </form>
@endforeach

@endsection