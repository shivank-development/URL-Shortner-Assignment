<!DOCTYPE html>
<html>
<head>
    <title>URL Shortener</title>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ccc; padding: 10px;">
        <h1>URL Shortener</h1>
        <div style="display: flex; gap: 10px;">
             @if($user->isSuperAdmin()) <span style="font-weight: bold; color: #d35400;">SUPER ADMIN</span> @endif
             <form action="/logout" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div style="color: green; margin: 10px 0;">{{ session('success') }}</div>
    @endif
    
    @if ($errors->any())
        <div style="color: red; margin: 10px 0;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- SUPER ADMIN SECTION --}}
    @if ($user->isSuperAdmin())
        {{-- Action Bar --}}
        <div style="margin-top: 20px; margin-bottom: 20px;">
            <a href="/invitations/create" style="text-decoration: none;">
                <button style="padding: 10px 20px; font-size: 16px; background-color: #007bff; color: white; border: none; cursor: pointer;">
                    + Invite New Client
                </button>
            </a>
        </div>

        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            
            {{-- CLIENTS WIDGET --}}
            <div style="flex: 1; min-width: 300px; border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="margin: 0;">Clients</h3>
                    <a href="/management/clients">View All</a>
                </div>
                <table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9f9f9;">
                            <th>Client Name</th>
                            <th>No of users</th>
                            <th>Total URLs</th>
                            <th>Total Hits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentCompanies as $company)
                            <tr>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->users_count }}</td>
                                <td>{{ $company->urls_count }}</td>
                                <td>{{ $company->urls_sum_clicks ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 10px; font-size: 0.9em; color: #666;">
                    Showing {{ $recentCompanies->count() }} of total {{ $totalCompanies }}
                </div>
            </div>

            {{-- URLS WIDGET --}}
            <div style="flex: 1; min-width: 300px; border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="margin: 0;">Short URLs</h3>
                    <div>
                        <a href="/urls/export" target="_blank" style="margin-right: 10px;">Download CSV</a>
                        <a href="/management/urls">View All</a>
                    </div>
                </div>
                <table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9f9f9;">
                            <th>Short URL</th>
                            <th>Long URL</th>
                            <th>Hits</th>
                            <th>Company</th>
                            <th>Created On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentUrls as $url)
                            <tr>
                                <td><a href="{{ url('/') . '/' . $url->short_code }}" target="_blank">{{ url('/') . '/' . $url->short_code }}</a></td>
                                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $url->original_url }}</td>
                                <td>{{ $url->clicks }}</td>
                                <td>{{ $url->company->name ?? 'N/A' }}</td>
                                <td>{{ $url->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 10px; font-size: 0.9em; color: #666;">
                    Showing {{ $recentUrls->count() }} of total {{ $totalUrls }}
                </div>
            </div>

        </div>
    @endif

    {{-- ADMIN SECTION --}}
    @if ($user->isAdmin())
        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
            
            {{-- GENERATED SHORT URLS WIDGET --}}
            <div style="flex: 2; min-width: 400px; border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <h3 style="margin: 0;">Generated Short URLs</h3>
                        <a href="/urls/create" target="_blank"><button style="cursor: pointer;">Generate URL</button></a>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <form action="/urls/export" method="GET" style="display: flex; gap: 5px; align-items: center;">
                            <select name="date_filter" style="padding: 5px; font-size: 12px;">
                                <option value="">All Time</option>
                                <option value="today">Today</option>
                                <option value="last_week">Week</option>
                                <option value="this_month">Month</option>
                            </select>
                            <button type="submit" style="font-size: 12px; cursor: pointer;">Download CSV</button>
                        </form>
                    </div>
                </div>
                <table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9f9f9;">
                            <th>Short URL</th>
                            <th>Long URL</th>
                            <th>Hits</th>
                            <th>User</th>
                            <th>Created On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentUrls as $url)
                            <tr>
                                <td><a href="{{ url('/') . '/' . $url->short_code }}" target="_blank">{{ url('/') . '/' . $url->short_code }}</a></td>
                                <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $url->original_url }}</td>
                                <td>{{ $url->clicks }}</td>
                                <td>{{ $url->user->name }}</td>
                                <td>{{ $url->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 0.9em; color: #666;">
                        Showing {{ $recentUrls->count() }} of total {{ $totalUrls }}
                    </div>
                    <a href="/management/urls" style="font-weight: bold; text-decoration: none;">View All &rarr;</a>
                </div>
            </div>

            {{-- TEAM MEMBERS WIDGET --}}
            <div style="flex: 1; min-width: 300px; border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="margin: 0;">Team Members</h3>
                    <a href="/invitations/user/create" target="_blank"><button style="cursor: pointer;">+ Invite Member</button></a>
                </div>
                <table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9f9f9;">
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Total Generated URLs</th>
                            <th>Total URL Hits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teamMembers as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->role }}</td>
                                <td>{{ $member->urls_count ?? 0 }}</td>
                                <td>{{ $member->urls_sum_clicks ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 0.9em; color: #666;">
                        Showing {{ $teamMembers->count() }} of total {{ $totalTeamMembers }}
                    </div>
                    <a href="/management/team" target="_blank" style="font-weight: bold; text-decoration: none;">View All &rarr;</a>
                </div>
            </div>

        </div>
    @endif

    {{-- MEMBER SECTION --}}
    @if ($user->isMember())
        <div style="margin-top: 20px;">
            <div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <h3 style="margin: 0;">Generated Short URLs</h3>
                        <a href="/urls/create"><button style="cursor: pointer; padding: 5px 15px;">Generate URL</button></a>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <form action="/urls/export" method="GET" style="display: flex; gap: 5px; align-items: center;">
                            <select name="date_filter" style="padding: 5px; font-size: 12px;">
                                <option value="">All Time</option>
                                <option value="today">Today</option>
                                <option value="last_week">Week</option>
                                <option value="this_month">Month</option>
                            </select>
                            <button type="submit" style="font-size: 12px; cursor: pointer;">Download CSV</button>
                        </form>
                    </div>
                </div>

                @if(request('search'))
                    <div style="margin-bottom: 15px;">
                        <a href="/dashboard">Clear Search</a>
                    </div>
                @endif

                <table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9f9f9;">
                            <th>Short URL</th>
                            <th>Long URL</th>
                            <th>Hits</th>
                            <th>Created On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($urls as $url)
                            <tr>
                                <td><a href="{{ url('/') . '/' . $url->short_code }}" target="_blank">{{ url('/') . '/' . $url->short_code }}</a></td>
                                <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $url->original_url }}</td>
                                <td>{{ $url->clicks }}</td>
                                <td>{{ $url->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        Showing {{ $urls->count() }} of total {{ $urls->total() }}
                    </div>
                    <div style="display: flex; gap: 5px;">
                        @if ($urls->onFirstPage())
                            <button disabled style="opacity: 0.5;">Previous</button>
                        @else
                            <a href="{{ $urls->previousPageUrl() }}"><button>Previous</button></a>
                        @endif

                        @if ($urls->hasMorePages())
                            <a href="{{ $urls->nextPageUrl() }}"><button>Next</button></a>
                        @else
                            <button disabled style="opacity: 0.5;">Next</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</body>
</html>
