<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Company Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Freelance Management System</h1>
    </div>

    <div class="content">
        <h2>Verify Your Company Account</h2>

        <p>Hello {{ $user->username }},</p>

        <p>Thank you for registering your company account with our Freelance Management System. To complete your registration and start using our platform, please verify your email address by clicking the button below:</p>

        <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>

        <p>If the button above doesn't work, you can also copy and paste this link into your browser:</p>
        <p><a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a></p>

        <p>This verification link will expire in 24 hours for security reasons.</p>

        <p>If you didn't create an account, please ignore this email.</p>

        <p>Best regards,<br>
        The Freelance Management System Team</p>
    </div>

    <div class="footer">
        <p>This email was sent to {{ $user->email }}. If you have any questions, please contact our support team.</p>
    </div>
</body>
</html>
