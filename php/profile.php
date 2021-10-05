<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit;
}
require_once "pdo.php";

/*
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();

*/
?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile Page</title>
		<link href=".././css/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Kalansat.fi</h1>
				<a href="home.php"><i class="fas fa-home"></i>Home</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Profile Page</h2>
			<?php
				if (isset($_SESSION['greeting']) ) {
					echo('<p style="color:green">');
					echo($_SESSION['greeting']);
					echo('</p>');
				}
				unset($_SESSION["greeting"]);

				if (isset($_SESSION['error']) ) {
					echo('<p style="color:red">');
					echo($_SESSION['error']);
					echo('</p>');
				}
				unset($_SESSION["error"]);
			?>
			<div>
				<p>You can manage your account on this page:</p>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td><b>Change password:</b></td>
					<!--</tr>
					<tr>
						<td></td>-->
						<td>
						<form action="changepassword.php" method="POST">
						<label for="currentpassword">Current password:</label><br>
						<input class="input" id="currentpassword" type="password" name="currentpassword" /><br>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
						<label for="newpassword">new password:</label><br>
						<input class="input" id="newpassword" type="password" name="newpassword" /><br>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
						<label for="reenteredpassword">re-enter new password:</label><br>
						<input class="input" id="reenteredpassword" type="password" name="reenteredpassword" /><br>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
						<input class="button6" type="submit" value="Submit">
						</td>
					</tr>				
				</table>
				

			</div>
		</div>
	</body>
</html>