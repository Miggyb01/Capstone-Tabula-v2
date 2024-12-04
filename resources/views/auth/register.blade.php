<!-- resources/views/firebase/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>
    <!-- Keep your existing CSS -->
</head>
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

    .signup_form {
        width: 100%;
        max-width: 450px;
        background: #fff;
        border-radius: 6px;
        padding: 41px 30px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .signup_form h3 {
        font-size: 25px;
        text-align: center;
        padding-bottom: 30px;
    }

    .signup_form p {
        text-align: center;
        font-weight: 500;
        margin-bottom: 20px;
    }

    .signup_form .separator {
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

    .method button {
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
        cursor: pointer;
        display: flex;
        align-items: center;
        color: #000;
    }

    .method button:hover {
        background: #f0ede5;
    }

	a {
		text-decoration: none;
		color: #3155FE;
		font-weight: 500;
	}

	a:hover {
		text-decoration: underline;
	}

    input[type="submit"] {
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
    
    input[type="submit"]:hover {
        background: #2a9df4;
    }

    @media(max-width: 584px) {
        .signup_form {
            max-width: 100%;
        }
        form .input_box {
            margin-bottom: 15px;
            width: 100%;
        }
    }

    .home-logo {
        display: block;
        margin: 0 auto;
        text-align: center;
        margin-bottom: 20px;
        width: 196px;
        height: 83px;
    }

    @media(max-width: 459px) {
        .signup_form {
            padding: 30px 20px;
        }
    }

    </style>
<body>
    <div class="signup_form">
        <img class="home-logo" src="{{ asset('tabulaLOGO.png') }}" alt="Tabula Logo">
        <h3>Sign up</h3>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="input_box">
                <input type="text" name="full_name" placeholder="Full Name" required value="{{ old('full_name') }}" />
                @error('full_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                <input type="text" name="username" placeholder="User Name" required value="{{ old('username') }}" />
                @error('username')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                <input type="password" name="password" placeholder="Password" required />
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                <!-- Add password confirmation field -->
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
                @error('password_confirmation')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <p class="options">Please enter your email address to receive a verification code.</p>
            
            <div class="input_box">
                <div class="input-wrapper">
                    <input type="email" name="email" placeholder="Email Address" required value="{{ old('email') }}" />
                    <i class="ri-google-fill"></i>
                </div>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="button">
                <input type="submit" value="SIGN UP">
            </div>

            <p class="text-center">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </form>
    </div>
</body>
</html>