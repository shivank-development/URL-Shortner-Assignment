<!DOCTYPE html>
<html>
<head>
    <title>Invite Team Member </title>
</head>
<body>
    <div style="padding: 20px; max-width: 600px; margin: 0 auto;">
        <h1>Invite Team Member</h1>
        <a href="/dashboard">Back to Dashboard</a>
        <br><br>

        @if (session('success'))
            <div style="padding: 15px; background-color: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px; word-break: break-all;">
                {{ session('success') }}
            </div>
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
            <form action="/invitations/user" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label>Email Address:</label><br>
                    <input type="email" name="email" placeholder="colleague@example.com" required style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Role:</label><br>
                    <select name="role" style="padding: 8px; width: 100%;">
                        <option value="Member">Member</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <button type="submit" style="padding: 10px 20px; font-size: 16px; background-color: #007bff; color: white; border: none; cursor: pointer;">Generate Invite Link</button>
            </form>
        </div>
    </div>
</body>
</html>
