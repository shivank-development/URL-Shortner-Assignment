<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $totalCompanies = \App\Models\Company::count();
            $recentCompanies = \App\Models\Company::withCount(['users', 'urls'])
                                                  ->withSum('urls', 'clicks')
                                                  ->latest()
                                                  ->take(3)
                                                  ->get();

            $totalUrls = \App\Models\Url::count();
            $recentUrls = \App\Models\Url::with(['user', 'company'])->latest()->take(3)->get();
            
            return view('dashboard.index', compact('user', 'recentCompanies', 'recentUrls', 'totalCompanies', 'totalUrls'));
        }

        if ($user->isAdmin()) {
            $totalUrls = \App\Models\Url::where('company_id', $user->company_id)->count();
            $recentUrls = \App\Models\Url::where('company_id', $user->company_id)
                                         ->with('user')
                                         ->latest()
                                         ->take(5)
                                         ->get();
            
            $totalTeamMembers = \App\Models\User::where('company_id', $user->company_id)->count();
            $teamMembers = \App\Models\User::where('company_id', $user->company_id)
                                           ->withCount('urls')
                                           ->withSum('urls', 'clicks')
                                           ->take(3)
                                           ->get();

            return view('dashboard.index', compact('user', 'recentUrls', 'teamMembers', 'totalUrls', 'totalTeamMembers'));
        }

        if ($user->isMember()) {
            $query = \App\Models\Url::where('user_id', $user->id);
        } else {
             $query = \App\Models\Url::query();
        }

        // Search
        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('original_url', 'like', "%{$search}%")
                  ->orWhere('short_code', 'like', "%{$search}%");
            });
        }

        // Pagination
        $urls = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard.index', compact('user', 'urls'));
    }
}
