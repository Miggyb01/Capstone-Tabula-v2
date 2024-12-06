<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
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

    .login_form {
        width: 550px;
        max-width: 435px;
        background: #fff;
        border-radius: 6px;
        padding: 41px 30px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .login_form h3 {
        font-size: 25px;
        text-align: center;
        padding-bottom: 30px;
    }

    .login_form p {
        text-align: center;
        font-weight: 500;
    }

    .login_form .separator {
        position: relative;
        margin-bottom: 24px;
    }

    form .input_box label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
    }

    form .input_box input {
        width: 100%;
        height: 57px;
        border: 1px solid #3155FE;
        border-radius: 15px;
        outline: none;
        background: #F9F6EE;
        font-size: 17px;
        padding: 0px 20px;
        margin-bottom: 25px;
        transition: 0.2s ease;
    }

    form .input_box input:focus {
        border-color: #3155FE;
    }

    form .input_box .password_title {
        display: flex;
        justify-content: space-between;
        text-align: center;
    }

    form .input_box {
        position: relative;
    }

    a {
        text-decoration: none;
        color: #3155FE;
        font-weight: 500;
    }

    a:hover {
        text-decoration: underline;
    }

    form .button button {
        width: 100%;
        height: 56px;
        border-radius: 15px;
        border: none;
        outline: none;
        background: #3155FE;
        color: #fff;
        font-size: 18px;
        font-weight: 500;
        text-transform: uppercase;
        cursor: pointer;
        margin-bottom: 28px;
        transition: 0.3s ease;
    }

    form button:hover {
        background: #2a9df4;
    }

    .text-danger {
        color: #dc3545;
        font-size: 14px;
        margin-top: -20px;
        margin-bottom: 15px;
        display: block;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .home-logo {
        display: block;
        margin: 0 auto;
        text-align: center;
        margin-bottom: 20px;
        width: 196px;
        height: 83px;
    }

    @media(max-width: 584px) {
        .login_form {
            max-width: 100%;
        }
    }

    @media(max-width: 459px) {
        .login_form {
            padding: 25px 20px;
        }
    }
    </style>
</head>
<body>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="login_form">
            <img class="home-logo" src="{{ asset('tabulaLOGO.png') }}" alt="Tabula Logo">
            <h3>Sign-In</h3>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="input_box">
                <input type="text" 
                       name="email" 
                       id="email" 
                       placeholder="Enter username or email address" 
                       value="{{ old('email') }}"
                       required />
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="input_box">
                <div class="password_title">
                </div>
                <input type="password" 
                       name="password" 
                       id="password" 
                       placeholder="Enter your password" 
                       required />
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <a href="#" class="float-end">Forgot Password?</a>
            <br><br>
            
            <div class="button">
                <button type="submit">Sign In</button>
            </div>

            <p class="sign_up">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
        </div>
    </form>

    @if(config('app.debug'))
        <div style="position: fixed; bottom: 0; right: 0; background: #f8f9fa; padding: 10px; border: 1px solid #ddd;">
            <small>Debug Info:</small><br>
            <small>Session: {{ json_encode(session()->all()) }}</small>
        </div>
    @endif
</body>
</html>