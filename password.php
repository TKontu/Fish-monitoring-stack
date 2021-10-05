<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

if (!isset($_SESSION['verified'])) {
	header('Location: index.php');
	exit;
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Recover password</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="./css/style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="register">
            <h1>Recover password</h1>
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
            </p>
			<form action="./php/changepassword.php" method="post" autocomplete="off">
                <label for="newpassword">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="newpassword" placeholder="New password" id="newpassword" required>
				<label for="reenteredpassword">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="reenteredpassword" placeholder="Repeat password" id="reenteredpassword" required>
				<input type="submit" value="Set up new password">
            </form>
            </form>
		</div>
	</body>
</html>