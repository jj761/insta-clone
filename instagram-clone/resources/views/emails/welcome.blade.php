<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #405DE6;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            color: white;
            margin: 0;
            font-size: 24px;
        }

        .body {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }

        .button {
            display: inline-block;
            background: #405DE6;
            color: white;
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Instagram Clone</h1>
    </div>
    <div class="body">
        <h2>Welcome, {{ $user->name }}!</h2>
        <p>Your account has been created successfully.</p>
        <p>Your username is <strong>{{ $user->username }}</strong>.</p>
        <p>Start sharing photos and connecting with people.</p>
    </div>
</body>

</html>
