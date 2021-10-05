<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Activation</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="./css/style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="register">
            <h1>Account activation</h1>
            <p class="logintext">
            <b>Activation code can be found in registration email.</b>
            </p>
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
			<form action="./php/activation.php" method="post" autocomplete="off">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="activationcode">
                    <i class="fas fa-key"></i>
				</label>
				<input type="text" name="activationcode" placeholder="Activation code" id="activationcode" required>
				<input type="submit" value="Activate account">
            </form>
            <form action="index.php" method="post">
                <input type="submit" value="Back to login">
            </form>
		</div>
	</body>
</html>