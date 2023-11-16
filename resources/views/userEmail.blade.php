<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333333;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        p {
            color: #666666;
            font-size: 16px;
            line-height: 1.5;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, {{ $name }}!</h1>
        <p>Your OTP code is: <strong>{{ $otp }}</strong></p>
        <p>The code is valid for 15 minutes only.</p>
    </div>
</body>
</html>
