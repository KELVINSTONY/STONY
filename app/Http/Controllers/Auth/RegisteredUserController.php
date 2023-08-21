<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *$table->string('firstName');
     * $table->string('lastName');
     * $table->date('birthdate');
     * $table->text('address');
     * $table->string('profilePicture')->nullable();
     * $table->string('email')->unique();
     * $table->timestamp('email_verified_at')->nullable();
     * $table->string('password');
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'birthdate' => ['required'],
            'address' => ['required', 'string', 'max:255'],
            'profilePicture' => 'required|image|max:2048',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $originalFilename = $request->file('profilePicture')->getClientOriginalName();
        $profilePicturePath = $request->file('profilePicture')->store('profile_pictures', 'public');

        $uploadedFile = $request->file('profilePicture');
        $originalFilename = $uploadedFile->getClientOriginalName();
        $newFilename = Str::random(20) . '_' . $originalFilename;
        $uploadedFile->storeAs('public/profile_pictures', $newFilename);



        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'birthdate' => $request->birthdate,
            'address' => $request->address,
            'profilePicture' => $newFilename,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('Sales');
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
