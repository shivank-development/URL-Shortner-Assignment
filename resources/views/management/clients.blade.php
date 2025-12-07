<!DOCTYPE html>
<html>
<head>
    <title>Clients - Super Admin</title>
</head>
<body>
    <h1>Clients</h1>
    <a href="/dashboard">Back to Dashboard</a>
    
    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Users</th>
                <th>Total Generated URLs</th>
                <th>Total URL Hits</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $company)
                <tr>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->users_count }}</td>
                    <td>{{ $company->urls_count }}</td>
                    <td>{{ $company->urls_sum_clicks ?? 0 }}</td>
                    <td>
                        <form action="/management/clients/{{ $company->id }}" method="POST" onsubmit="return confirm('Are you sure? This will delete all users and URLs for this company.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red;">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            Showing {{ $companies->count() }} of total {{ $companies->total() }}
        </div>
        <div style="display: flex; gap: 5px;">
            @if ($companies->onFirstPage())
                <button disabled style="opacity: 0.5;">Previous</button>
            @else
                <a href="{{ $companies->previousPageUrl() }}"><button>Previous</button></a>
            @endif

            @if ($companies->hasMorePages())
                <a href="{{ $companies->nextPageUrl() }}"><button>Next</button></a>
            @else
                <button disabled style="opacity: 0.5;">Next</button>
            @endif
        </div>
    </div>
</body>
</html>
