<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
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

        .verify-email-container {
            width: 550px;
            max-width: 500px;
            background: #fff;
            border-radius: 15px;
            padding: 41px 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .card-header h3 {
            font-size: 25px;
            color: #333;
        }

        .verification-icon {
            font-size: 48px;
            color: #3155FE;
            display: block;
            text-align: center;
            margin: 20px 0;
        }

        .verification-text {
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            line-height: 1.5;
        }

        .instructions {
            text-align: center;
            color: #666;
            margin: 20px 0;
            line-height: 1.6;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
        }

        .alert-danger {
            color: #842029;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }

        .resend-button {
            display: block;
            width: 100%;
            padding: 15px;
            background: #3155FE;
            color: white;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            margin-top: 20px;
            transition: background 0.3s ease;
        }

        .resend-button:hover {
            background: #2a9df4;
        }

        .home-logo {
            display: block;
            margin: 0 auto;
            text-align: center;
            margin-bottom: 20px;
            width: 196px;
            height: 83px;
        }
    </style>
</head>
<body>
    <div class="verify-email-container">
        <img class="home-logo" src="{{ asset('tabulaLOGO.png') }}" alt="Tabula Logo">
        
        <div class="card-header">
            <h3>Verify Your Email</h3>
        </div>

        <i class="ri-mail-send-line verification-icon"></i>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <p class="verification-text">
            We've sent a verification email to:<br>
            <strong>{{ $email }}</strong>
        </p>

        <p class="instructions">
            Please check your email and click the verification link to complete your registration.
            If you don't see the email, check your spam folder.
        </p>

        <form action="{{ route('verification.resend') }}" method="POST">
            @csrf
            <button type="submit" class="resend-button">
                Resend Verification Email
            </button>
        </form>
    </div>
</body>
</html>