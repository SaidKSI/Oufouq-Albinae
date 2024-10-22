<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function index()
    {
        return view('auth.login');
    }

    function submit(Request $request)
    {

        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        $user = User::where('email', $formFields['email'])->first();
        if ($user && !$user->blocked) {
            // Attempt to authenticate the user
            if (auth()->attempt($formFields)) {
                $request->session()->regenerate();
                return redirect()->route('dashboard.index');
            }
        } elseif ($user && $user->blocked) {
            // If the user is blocked, return a response indicating that
            toastr()->error('Your account is blocked. Please contact support for assistance.');
            return back()->withErrors(['email' => 'Your account is blocked. Please contact support for assistance.'])->withInput($request->only('email'));
        }
        toastr()->error('Invalid credentials');
        // If the credentials are invalid or the user doesn't exist, return to login with an error message
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}