<!DOCTYPE html>
<html>
<head>
    <title>Invite New Client - Super Admin</title>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ccc; padding: 10px;">
        <h1>Invite New Client</h1>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
    
    <div style="padding: 20px;">
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

        <div style="background-color: #f5f5f5; padding: 20px; border-radius: 8px; max-width: 500px;">
            <form action="/invitations/company" method="POST">
                @csrf
                <div style="margin-bottom: 10px;">
                    <label>Company Name:</label><br>
                    <input type="text" name="company_name" required style="width: 100%;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label>Admin Name:</label><br>
                    <input type="text" name="name" required style="width: 100%;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label>Admin Email:</label><br>
                    <input type="email" name="email" required style="width: 100%;">
                </div>
                <button type="submit" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; cursor: pointer;">Send Invitation / Create</button>
            </form>
        </div>
    </div>
</body>
</html>
