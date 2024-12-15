<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify Email</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Source Sans Pro', sans-serif;
        }

        body {
            width: 100%;
            min-height: 100vh;
            padding: 0 10px;
            display: flex;
            background: #F5F5F5;
            justify-content: center;
            align-items: center;
        }

        .verify-prompt {
            width: 550px;
            max-width: 435px;
            background: #fff;
            border-radius: 6px;
            padding: 41px 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .verify-prompt img {
            width: 196px;
            height: 83px;
            margin-bottom: 20px;
        }

        .verify-prompt h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .verify-prompt p {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .verify-prompt .email {
            font-weight: bold;
            color: #3155FE;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            margin: 20px auto;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3155FE;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="verify-prompt">
        <img src="{{ asset('tabulaLOGO.png') }}" alt="Tabula Logo">
        <h3>Please Verify Your Email</h3>
        <p>We've sent a verification email to:<br>
           <span class="email">{{ $email }}</span></p>
        <p>Please check your email and click the verification link to continue.</p>
        <div class="loading-spinner"></div>
        <p>Waiting for verification...</p>
    </div>
</body>
</html>