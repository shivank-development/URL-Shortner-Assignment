<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $user = Auth::user();
        
        $query = Url::query();

        if ($user->isSuperAdmin()) {
            $query->with(['company', 'user']);
        } elseif ($user->isAdmin()) {
            $query->where('company_id', $user->company_id)->with('user');
        } elseif ($user->isMember()) {
            $query->where('user_id', $user->id);
        }

        // Date Filtering
        $filter = $request->query('date_filter');
        if ($filter === 'today') {
            $query->whereDate('created_at', \Carbon\Carbon::today());
        } elseif ($filter === 'last_week') {
            $query->whereBetween('created_at', [\Carbon\Carbon::now()->subWeek()->startOfWeek(), \Carbon\Carbon::now()->subWeek()->endOfWeek()]);
        } elseif ($filter === 'this_month') {
            $query->whereMonth('created_at', \Carbon\Carbon::now()->month)
                  ->whereYear('created_at', \Carbon\Carbon::now()->year);
        }

        $urls = $query->latest()->get();

        $response = new StreamedResponse(function () use ($urls, $user) {
            $handle = fopen('php://output', 'w');

            // Headers
            $columns = ['Short Code', 'Original URL', 'Hits', 'Created At'];
            if ($user->isSuperAdmin()) {
                array_push($columns, 'Company', 'Created By');
            } elseif ($user->isAdmin()) {
                array_push($columns, 'Created By');
            }
            
            fputcsv($handle, $columns);

            foreach ($urls as $url) {
                $row = [
                    $url->short_code,
                    $url->original_url,
                    $url->clicks,
                    $url->created_at->toDateTimeString(),
                ];

                if ($user->isSuperAdmin()) {
                    array_push($row, $url->company->name ?? 'N/A', $url->user->name);
                } elseif ($user->isAdmin()) {
                    array_push($row, $url->user->name);
                }

                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="urls.csv"');

        return $response;
    }
}
