<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Nick & Ollie's Wedding Photo Gallery</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 0; padding: 0; background: #faf8f5; color: #3d3530; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .card { background: #fff; border-radius: 12px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        h1 { color: #8b7355; font-size: 24px; margin-bottom: 8px; }
        .subtitle { color: #7a726a; font-size: 14px; margin-bottom: 24px; }
        p { line-height: 1.6; font-size: 15px; }
        .btn { display: inline-block; background: #8b7355; color: #fff !important; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; margin: 16px 0; }
        .features { margin: 20px 0; padding: 0; list-style: none; }
        .features li { padding: 8px 0; border-bottom: 1px solid #f0ebe5; }
        .features li:last-child { border-bottom: none; }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Welcome, {{ $firstname }}!</h1>
            <p class="subtitle">You're now part of Nick & Ollie Fortune's wedding celebration.</p>

            <p>Thank you for joining our wedding photo gallery! You can now:</p>

            <ul class="features">
                <li>📸 <strong>Upload</strong> your favorite photos from the celebration</li>
                <li>🖼️ <strong>Browse</strong> the gallery and see what others have shared</li>
                <li>🏆 <strong>Enter contests</strong> and vote for your favorites</li>
                <li>💬 <strong>Leave comments</strong> and connect with other guests</li>
                <li>📇 <strong>Find contacts</strong> in the guest phonebook</li>
            </ul>

            <a href="{{ $loginUrl }}" class="btn">Get Started</a>

            <p style="margin-top: 24px; font-size: 14px; color: #7a726a;">
                With love,<br>
                <strong>Nick & Ollie</strong>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Nick & Ollie Fortune. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
