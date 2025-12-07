<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    // Show the form to invite a new company
    public function createCompany()
    {
        if (!Auth::user()->isSuperAdmin()) abort(403);
        return view('invitations.create_company');
    }

    public function createUser()
    {
        if (!Auth::user()->isAdmin()) abort(403);
        return view('invitations.create_user');
    }

    // SuperAdmin creates a new Company and its first Admin
    public function storeCompany(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ]);

        $company = Company::create(['name' => $request->company_name]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'), // Default password
            'role' => 'Admin',
            'company_id' => $company->id,
        ]);

        return back()->with('success', 'Company and Admin created successfully.');
    }

    // Admin invites a new user to their company (Now generates a link)
    public function storeUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'role' => 'required|in:Admin,Member',
        ]);

        $user = Auth::user();

        if (!$user->isAdmin()) abort(403);

        // Generate Signed URL
        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'register', 
            now()->addDays(7), 
            [
                'email' => $request->email, 
                'role' => $request->role, 
                'company_id' => $user->company_id
            ]
        );

        // In a real app, we would Mail::to($request->email)->send(...);
        // For this demo, we will just flash the link to the session.
        
        return back()->with('success', 'Invitation Link Generated: ' . $url);
    }
}
