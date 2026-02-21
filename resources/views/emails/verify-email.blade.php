<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background-color: #007bff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
        }
        .content {
            padding: 40px;
            text-align: center;
        }
        .content h2 {
            color: #333333;
            margin-bottom: 20px;
        }
        .content p {
            color: #666666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            color: #999999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Techstream Media</h1>
        </div>
        <div class="content">
            <h2>Verify Your Email Address</h2>
            <p>Dear {{ $user->name }},</p>
            <p>Thank you for registering with Techstream Media. Please click the button below to verify your email address and activate your account.</p>
            <a href="{!! $url !!}" class="button">Verify Email Address</a>
            <p>If you did not create this account, please ignore this email.</p>
            <p>Thank you,<br>Techstream Media Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Techstream Media. All rights reserved.</p>
        </div>
    </div>
</body>
</html>