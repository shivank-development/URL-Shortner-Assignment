<!DOCTYPE html>
<html>
<head>
    <title>Manage Team</title>
</head>
<body>
    <h1>Manage Team</h1>
    <a href="/dashboard">Back to Dashboard</a>

    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Total Generated URLs</th>
                <th>Total URL Hits</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $member)
                <tr>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->role }}</td>
                    <td>{{ $member->urls_count ?? 0 }}</td>
                    <td>{{ $member->urls_sum_clicks ?? 0 }}</td>
                    <td>
                        @if ($member->id !== auth()->id())
                            <form action="/management/team/{{ $member->id }}" method="POST" onsubmit="return confirm('Remove this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: red;">Remove</button>
                            </form>
                        @else
                            (You)
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            Showing {{ $members->count() }} of total {{ $members->total() }}
        </div>
        <div style="display: flex; gap: 5px;">
            @if ($members->onFirstPage())
                <button disabled style="opacity: 0.5;">Previous</button>
            @else
                <a href="{{ $members->previousPageUrl() }}"><button>Previous</button></a>
            @endif

            @if ($members->hasMorePages())
                <a href="{{ $members->nextPageUrl() }}"><button>Next</button></a>
            @else
                <button disabled style="opacity: 0.5;">Next</button>
            @endif
        </div>
    </div>
</body>
</html>
