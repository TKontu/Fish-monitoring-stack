<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link href="./css/style.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?always=1"></script>		
	</head>
	<body>
		<div class="login">
			<h1>Login</h1>
			<div>
				<?php
					if (isset($_SESSION['greeting']) ) {
						echo('<p class="loginprompt" style="color:green">');
						echo($_SESSION['greeting']);
						echo('</p>');
					}
					unset($_SESSION["greeting"]);

					if (isset($_SESSION['error']) ) {
						echo('<p class="loginprompt" style="color:red">');
						echo($_SESSION['error']);
						echo('</p>');
					}
					unset($_SESSION["error"]);
				?>
			</div>
			<form action="./php/authenticate.php" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" value="Login">
			</form>
			<form action="recovery.php" method="post">
				<input type="submit" value="Lost your password?">
			</form>
			<form action="registerscreen.php" method="post">
				<input type="submit" value="Register">
			</form>
		</div>
	</body>
</html>