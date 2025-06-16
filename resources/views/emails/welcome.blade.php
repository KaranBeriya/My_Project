<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome Email</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #dfe9f3, #ffffff);
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-in;
        }

        .container {
            max-width: 680px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            padding: 40px;
            animation: fadeIn 1.2s ease-in-out;
        }

        .header {
            background: linear-gradient(45deg, #007bff, #00c6ff);
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        h2 {
            color: #2c3e50;
            margin-top: 20px;
        }

        p {
            font-size: 16px;
            color: #444;
            line-height: 1.7;
        }

        .info {
            background-color: #f1f1f1;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 6px;
        }

        .info p {
            margin: 8px 0;
            display: flex;
            align-items: center;
        }

        .info strong {
            width: 130px;
            font-weight: 600;
            color: #333;
        }

        .button-wrapper {
            text-align: center;
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            background: linear-gradient(45deg, #007bff, #00c6ff);
            color: white !important;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .button:hover {
            background: linear-gradient(45deg, #0056b3, #0096c7);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Welcome to Our App</div>

        <h2>Hello, {{ $user->name }}!</h2>

        <p>Thank you for joining us. Here are your registration details:</p>

        <div class="info">
            <p><strong>Name :- </strong> {{ $user->name }}</p>
            <p><strong>Email :- </strong> {{ $user->email }}</p>
            <p><strong>Contact :- </strong> {{ $user->contact ?? 'N/A' }}</p>
        </div>

        <p>Please verify your email to activate your account:</p>

        <div class="button-wrapper">
            <a href="{{ $verificationUrl }}" class="button">Verify Email</a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} YourAppName. All rights reserved.
        </div>
    </div>
</body>
</html>
