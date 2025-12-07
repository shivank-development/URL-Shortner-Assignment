<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;

class ManagementController extends Controller
{
    public function clients()
    {
        $this->authorizeSuperAdmin();
        $companies = Company::withCount(['users', 'urls'])
                            ->withSum('urls', 'clicks')
                            ->paginate(10);
        return view('management.clients', compact('companies'));
    }

    public function destroyClient(Company $company)
    {
        $this->authorizeSuperAdmin();
        $company->delete(); 
        return back()->with('success', 'Company deleted successfully.');
    }

    public function urls(Request $request)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && !$user->isAdmin()) abort(403);
        
        $query = \App\Models\Url::with(['user', 'company'])->latest();

        if ($user->isAdmin()) {
            $query->where('company_id', $user->company_id);
        }

        $urls = $query->paginate(15);
        
        return view('management.urls', compact('urls'));
    }

    public function team()
    {
        $user = Auth::user();
        if (!$user->isAdmin()) abort(403);

        $members = User::where('company_id', $user->company_id)
                       ->withCount('urls')
                       ->withSum('urls', 'clicks')
                       ->paginate(10);
        return view('management.team', compact('members'));
    }

    public function destroyMember(User $member)
    {
        $user = Auth::user();
        if (!$user->isAdmin() || $member->company_id !== $user->company_id) abort(403);
        
        $member->delete();
        return back()->with('success', 'Team member removed.');
    }

    private function authorizeSuperAdmin()
    {
        if (!Auth::user()->isSuperAdmin()) abort(403);
    }
}
