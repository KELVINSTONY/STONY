<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionController extends Controller
{
    public function index()
{
    $users = User::all();
    $permissions = Permission::all();
    return view('permissions.index', compact('users', 'permissions'));
}

public function update(User $user)
{
    $user->syncPermissions(request('permissions'));
    return redirect()->route('permissions.index')->with('success', 'Permissions updated successfully');
}
}
