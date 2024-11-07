<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title> Login </title>
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
		width: 100%;
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

	/* Login Option Seperator */
	.login_form .separator span {
		background: #fff;
		z-index: 1;
		padding: 0 10px;
		position: relative;
	}

	.login_form .separator::after {
		content: '';
		position: absolute;
		width: 100%;
		top: 50%;
		left: 0;
		height: 1px;
		background: #C2C2C2;
		display: block;
	}

	form .input_box label {
		display: block;
		font-weight: 500;
		margin-bottom: 8px;
	}

	/* Input Fields */
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

	/* Para sa Login Style */
	form .button input {
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
	/* Responsive media query code for mobile devices */
	@media(max-width: 584px) {
	  .container {
		max-width: 100%;
	  }
	  form .user-details .input-box {
		margin-bottom: 15px;
		width: 100%;
	  }
	  form .category {
		width: 100%;
	  }
	  .content form .user-details {
		max-height: 300px;
		overflow-y: scroll;
	  }
	  .user-details::-webkit-scrollbar {
		width: 5px;
	  }
	}
	/* Responsive media query code for mobile devices */
	@media(max-width: 459px) {
	  .container .content .category {
		flex-direction: column;
	  }
	}
	</style>
	</head>
	<body>
		<div class="login_form">
			<!-- Login form container -->
			<form action="#">
			<h3>Sign-In</h3>

			<!-- Email input box -->
			<div class="input_box">
			<input type="email" name="email" id="email" placeholder="Enter email address / Username" required />
			</div>

			<!-- Paswwrod input box -->
			<div class="input_box">
			<div class="password_title">
			</div>
			
			<input type="password" name="password" id="password" placeholder="Enter your password" required />
			</div>

			<div class="button">
			<input type="submit" value="Next">
			<a href="#" class="float-end">Forgot Password?</a>
			</div>			  
			<!-- Login option separator -->
			<br>
			  
			<div class="input_box">
			<input type="text" name="verify" id="verify" placeholder="Enter Verification Code" required />
			</div>

			<!-- Login button -->
			<div class="button">
			<input type="submit" value="SIGN-IN">
			</div>

			<p class="sign_up">Don't have an account? <a href="#">Sign up</a></p>
			</form>
		</div>
	</body>
</html>