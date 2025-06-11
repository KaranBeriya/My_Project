<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 560px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h2 {
            color: #2c3e50;
        }
        p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }
        .info {
            background-color: #f1f1f1;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info strong {
            display: inline-block;
            width: 100px;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, {{ $user->name }}!</h2>

        <p>Thank you for registering. Below are your registration details:</p>

        <div class="info">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Contact:</strong> {{ $user->contact ?? 'N/A' }}</p>
        </div>

        <p>Please verify your email to complete registration:</p>

        <a href="{{ $verificationUrl }}" class="button">Verify Email</a>

        <div class="footer">
            &copy; {{ date('Y') }} YourAppName. All rights reserved.
        </div>
    </div>
</body>
</html>
