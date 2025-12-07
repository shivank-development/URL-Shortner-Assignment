<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UrlController extends Controller
{
    public function create()
    {
        return view('urls.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
        ]);

        $user = Auth::user();

        if ($user->isSuperAdmin()) {
             abort(403, 'SuperAdmin cannot create short URLs.');
        }

        $url = Url::create([
            'original_url' => $request->original_url,
            'short_code' => Str::random(6),
            'user_id' => $user->id,
            'company_id' => $user->company_id,
        ]);

        return back()->with('success', 'Short URL created: ' . $url->short_code);
    }

    public function redirect(Request $request, $shortCode)
    {
        $url = Url::where('short_code', $shortCode)->firstOrFail();
        
        $url->increment('clicks');

        \App\Models\ClickLog::create([
            'url_id' => $url->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect($url->original_url);
    }
}
