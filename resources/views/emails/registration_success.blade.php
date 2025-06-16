<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration Successful</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            padding: 30px;
        }
        .email-container {
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }
        h2 {
            color: #4a90e2;
        }
        p {
            font-size: 16px;
            color: #333333;
        }
        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #777;
            text-align: center;
        }
        .info-box {
            background: #f0f4ff;
            padding: 10px 15px;
            border-radius: 6px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Welcome, {{ $user->name }} 🎉</h2>

        <p>We're excited to have you on board. You have successfully registered!</p>

        <div class="info-box">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Contact:</strong> {{ $user->contact ?? 'N/A' }}</p>
        </div>

        <p>Thank you for joining us!</p>

        <div class="footer">
            &co
