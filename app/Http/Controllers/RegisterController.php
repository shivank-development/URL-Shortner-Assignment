<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or Expired Invitation Link');
        }

        return view('auth.register', [
            'email' => $request->email,
            'role' => $request->role,
            'company_id' => $request->company_id,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'company_id' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email, 
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'company_id' => $request->company_id,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
