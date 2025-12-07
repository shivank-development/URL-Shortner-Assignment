<!DOCTYPE html>
<html>
<head>
    <title>Generate Short URL</title>
</head>
<body>
    <div style="padding: 20px; max-width: 600px; margin: 0 auto;">
        <h1>Generate New Short URL</h1>
        <a href="/dashboard">Back to Dashboard</a>
        <br><br>

        @if (session('success'))
            <div style="color: green;">{{ session('success') }}</div>
        @endif
        
        @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="border: 1px solid #ccc; padding: 20px; border-radius: 8px;">
            <form action="/urls" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label>Original URL:</label><br>
                    <input type="url" name="original_url" placeholder="https://example.com" required style="width: 100%; padding: 8px;">
                </div>
                <button type="submit" style="padding: 10px 20px; font-size: 16px; background-color: #28a745; color: white; border: none; cursor: pointer;">Generate</button>
            </form>
        </div>
    </div>
</body>
</html>
