<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit;
}
require_once "pdo.php";
require_once "pdotraps.php";

$sql = "SELECT trapcount, traps, nicknames FROM usersettings WHERE user_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':id' => $_SESSION['id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

			$trapcount = $row['trapcount'];
			$traps = $row['traps'];
			$trapsarray = explode(",", $traps);
			$nicknames = unserialize($row['nicknames']);

$i = 0;
while ($i < $trapcount) {

	$stmt = $pdotraps->query("SELECT * FROM ".$trapsarray[$i]."");
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$amount[$i] = 0;

	foreach ( $rows as $row ) {
		$amount[$i]++;		
	}
	$i++;

} 

$i = 0;
while ($i < $trapcount) {
	$sql = "SELECT latitude, longitude FROM trapmetadata WHERE trap_code = :trap_code";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':trap_code' => $trapsarray[$i]));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				$latitudes[$i] = $row['latitude'];
				$longitudes[$i] = $row['longitude'];

				$i++;


}

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
			<h2>Settings Page</h2>
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
				<p><?=$_SESSION['name']?>, your account's trap details are below:</p>
				<table>
					<tr>
						<td colspan="2">Your traps:</td>
						<td><?$row[trapcount]?></td>
					</tr>
					<?php
						$i = 0;
						while ($i < $trapcount) {
							echo "<tr><td>";
							echo('<b>Trap ID: </b>');
							echo("</td><td>");
							echo($trapsarray[$i]);
							echo("</td><td>");
							echo('<form action="addnickname.php?trap='.$trapsarray[$i].'" method="get">');
								echo('<label for="nickname">Nickname:</label><br>');
								if ($nicknames !== FALSE){
									if (array_key_exists($i,$nicknames)){
										echo('<input class="input" id="nickname" type="text" name="nickname" value="'.$nicknames[$i].'" /><br>');
									} else {
										echo('<input class="input" id="nickname" type="text" name="nickname" /><br>');
									}
								} else {
									echo('<input class="input" id="nickname" type="text" name="nickname" /><br>');
								}
								echo('<input type="hidden" name="trapid" value="'.$trapsarray[$i].'"/>');
								echo('<input class="button6" type="submit" value="Submit">');
							echo("</form>");
							echo("</td><td>");
							echo('<form action="addlatitude.php?trap='.$trapsarray[$i].'" method="get">');
								echo('<label for="latitude">Latitude:</label><br>');
								if ($latitudes !== FALSE){
									if (array_key_exists($i,$latitudes)){
										echo('<input class="input" id="latitude" type="text" name="latitude" value="'.$latitudes[$i].'" /><br>');
									} else {
										echo('<input class="input" id="latitude" type="text" name="latitude" /><br>');
									}
								} else {
									echo('<input class="input" id="latitude" type="text" name="latitude" /><br>');
								}
								echo('<input type="hidden" name="trapid" value="'.$trapsarray[$i].'"/>');
								echo('<input class="button6" type="submit" value="Submit">');
							echo("</form>");
							echo("</td><td>");
							echo('<form action="addlongitude.php?trap='.$trapsarray[$i].'" method="get">');
								echo('<label for="longitude">Longitude:</label><br>');
								if ($longitudes !== FALSE){
									if (array_key_exists($i,$longitudes)){
										echo('<input class="input" id="longitude" type="text" name="longitude" value="'.$longitudes[$i].'" /><br>');
									} else {
										echo('<input class="input" id="longitude" type="text" name="longitude" /><br>');
									}
								} else {
									echo('<input class="input" id="longitude" type="text" name="longitude" /><br>');
								}
								echo('<input type="hidden" name="trapid" value="'.$trapsarray[$i].'"/>');
								echo('<input class="button6" type="submit" value="Submit">');
							echo("</form>");
							echo("</td><td>");
							echo('<a href="removetrap.php?trap_id='.$trapsarray[$i].'" onclick="return confirm(\'Are you sure, you want to delete it?\')"class="button6">Remove trap</a>');
							echo("</td></tr>\n");
							$i++;
						}
					?>
					<tr>
						<td colspan="3"><a href="addtrap.php"class="button6">Add new trap</a></td>
					</tr>				
				</table>
			</div>
		</div>
	</body>
</html>