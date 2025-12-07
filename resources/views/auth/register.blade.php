<!DOCTYPE html>
<html>
<head>
    <title>Accept Invitation - Sembark URL Shortener</title>
</head>
<body>
    <h1>Complete Registration</h1>
    <form method="POST" action="/register">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="role" value="{{ $role }}">
        <input type="hidden" name="company_id" value="{{ $company_id }}">

        <div>
            <label>Email: {{ $email }}</label>
        </div>
        <div>
            <label>Full Name:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <button type="submit">Join Team</button>
    </form>
</body>
</html>
