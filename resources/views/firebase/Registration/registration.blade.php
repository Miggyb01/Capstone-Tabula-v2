<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Sign Up</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Source Sans Pro', sans-serif;
		}
		body {
			display: flex;
			justify-content: center;
			align-items: center;
			width: 100%;
			min-height: 100vh;
			background: #F5F5F5;
		}
		.signup_form {
			width: 100%;
			max-width: 400px;
			background: #fff;
			border-radius: 10px;
			padding: 40px 30px;
			box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
			text-align: center;
		}
		.signup_form img {
			width: 60px;
			height: 60px;
			margin-bottom: 20px;
		}
		.signup_form h3 {
			font-size: 24px;
			margin-bottom: 20px;
		}
		.signup_form .input_box input {
			width: 100%;
			height: 50px;
			border: 1px solid #ddd;
			border-radius: 25px;
			background: #F5F5F5;
			font-size: 16px;
			padding: 0 20px;
			margin-bottom: 15px;
			outline: none;
			transition: border-color 0.3s;
		}
		.signup_form .input_box input:focus {
			border-color: #3155FE;
		}
		.signup_form .options {
			font-size: 14px;
			margin: 20px 0;
			font-weight: 500;
			text-align: center;
		}
		.signup_form .method {
			display: flex;
			justify-content: space-around;
			margin-bottom: 20px;
		}
		.signup_form .method button {
			width: 100%;
			height: 50px;
			border: none;
			background: #F5F5F5;
			border-radius: 25px;
			color: black;
			font-size: 16px;
			font-weight: 500;
			cursor: pointer;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-bottom: 10px;
		}
		.signup_form .button {
			width: 100%;
		}
		.signup_form .button input {
			width: 100%;
			height: 50px;
			border: none;
			background: #3155FE;
			border-radius: 25px;
			color: #fff;
			font-size: 18px;
			font-weight: 500;
			cursor: pointer;
			transition: background 0.3s;
		}
		.signup_form .button input:hover {
			background: #2a9df4;
		}
	</style>
</head>
<body>
	<div class="signup_form">
		
		<h3>Sign up</h3>
		<form action="#">
			<div class="input_box">
				<input type="text" name="first_name" placeholder="First Name" required />
				<input type="text" name="middle_name" placeholder="Middle Name" />
				<input type="text" name="last_name" placeholder="Last Name" required />
				<input type="text" name="username" placeholder="User Name" required />
				<input type="password" name="password" placeholder="Password" required />
			</div>
			<p class="options">Choose a method for receiving your verification code:</p>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">

                <div class="method">
                    <button type="button">
                        <i class="ri-google-fill" style="margin-right: 10px; color:#3155FE"></i> Sign up with Gmail
                    </button>
                </div>
                <div class="method">
                    <button type="button">
                        <i class="ri-phone-fill" style="margin-right: 10px; color:#3155FE"></i> Enter your Phone Number
                    </button>
                </div>
                <div class="method">
                    <button type="button">
                        <i class="ri-facebook-fill" style="margin-right: 10px; color:#3155FE"></i> Sign up with Facebook
                    </button>



					
                </div>

				<form class="inline-form">
					<input type="text" placeholder="Username">
					<input type="password" placeholder="Password">
					<button type="submit">Sign In</button>
				</form>

			<div class="button">
				<input type="submit" value="Next">
			</div>
		</form>
	</div>
</body>
</html>
