<!DOCTYPE html>
<html>
<head>
    <title>All Generated URLs - Super Admin</title>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ccc; padding: 10px;">
        <h1>All Generated Short URLs</h1>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    <div style="padding: 20px;">
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <a href="/dashboard">Back to Dashboard</a>
            
            <form action="/urls/export" method="GET" style="display: flex; gap: 10px; align-items: center;">
                <label>Export Filter:</label>
                <select name="date_filter" style="padding: 5px;">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="last_week">Last Week</option>
                    <option value="this_month">This Month</option>
                </select>
                <button type="submit" style="padding: 5px 15px; cursor: pointer;">Download CSV</button>
            </form>
        </div>

        <table border="1" cellpadding="8" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th>Short URL</th>
                    <th>Long URL</th>
                    <th>Hits</th>
                    <th>Company</th>
                    <th>Created On</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($urls as $url)
                    <tr>
                        <td><a href="{{ url('/') . '/' . $url->short_code }}" target="_blank">{{ url('/') . '/' . $url->short_code }}</a></td>
                        <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $url->original_url }}</td>
                        <td>{{ $url->clicks }}</td>
                        <td>{{ $url->company->name ?? 'N/A' }}</td>
<!DOCTYPE html>
<html>
<head>
    <title>All Generated URLs</title>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ccc; padding: 10px;">
        <h1>All Generated Short URLs</h1>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    <div style="padding: 20px;">
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <a href="/dashboard">Back to Dashboard</a>
            
            <form action="/urls/export" method="GET" style="display: flex; gap: 10px; align-items: center;">
                <label>Export Filter:</label>
                <select name="date_filter" style="padding: 5px;">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="last_week">Last Week</option>
                    <option value="this_month">This Month</option>
                </select>
                <button type="submit" style="padding: 5px 15px; cursor: pointer;">Download CSV</button>
            </form>
        </div>

        <table border="1" cellpadding="8" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th>Short URL</th>
                    <th>Long URL</th>
                    <th>Hits</th>
                    <th>Company</th>
                    <th>Created On</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($urls as $url)
                    <tr>
                        <td><a href="{{ url('/') . '/' . $url->short_code }}" target="_blank">{{ url('/') . '/' . $url->short_code }}</a></td>
                        <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $url->original_url }}</td>
                        <td>{{ $url->clicks }}</td>
                        <td>{{ $url->company->name ?? 'N/A' }}</td>
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
</body>
</html>
