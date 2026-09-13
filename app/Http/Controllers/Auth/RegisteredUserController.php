<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,manager,user'],
        ]);

        $isFirstUser = User::count() === 0;
        $role = $request->input('role', 'user');

        if ($isFirstUser) {
            $role = 'admin';
        }

        $isApproved = $isFirstUser;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'is_approved' => $isApproved,
            'approved_at' => $isApproved ? now() : null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        if (! $user->isApproved() && ! $user->isAdmin()) {
            return redirect()->route('pending');
        }

        if (! $user->isApproved() && $user->isAdmin()) {
            return redirect()->route('pending');
        }

        return redirect(route('dashboard', absolute: false));
    }
}
