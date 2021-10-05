<?php
require_once "pdotraps.php";
require_once "pdoadmin.php";
require_once "pdo.php";
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit;
}

if ($_SESSION['accounttype'] == 0) {
	header('Location: home.php');
	exit;
}

//Fetching and parsing account data from all accounts:
$stmt = $pdo->query("SELECT username, activation_code, account_type FROM accounts");
$accountdata = $stmt->fetchAll(PDO::FETCH_ASSOC);

//var_dump($accountdata);
//echo('<br>');
//echo(count($accountdata));

$accountcount = count($accountdata);

//Fetching and parsing account data from all traps:
$stmt = $pdo->query("SELECT trap_code, latitude, longitude FROM trapmetadata");
$trapdata = $stmt->fetchAll(PDO::FETCH_ASSOC);

//var_dump($accountdata);
//echo('<br>');
//echo(count($accountdata));

$trapcount = count($trapdata);

$i = 0;
while ($i < $trapcount) {
	$stmt = $pdotraps->query("SELECT * FROM ".$trapdata[$i]['trap_code']."");
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	
	$trapdata[$i]['fishamount'] = count($rows);
//	if(is_null($rows)){
//		$trapdata[$i]['fishamount'] = 0;
//	}
	$i++;

} 

//var_dump($trapdata);

$stmt = $pdoadmin->query("SELECT * FROM fishhistory");
$fishdata = $stmt->fetchAll(PDO::FETCH_ASSOC);

$fishcount = count($fishdata);

?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Administration Page</title>
		<link href=".././css/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<script type="text/javascript" src=".././scripts/Export_fish.js"></script>
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
			<h2>Administration Page</h2>
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
			<p><b>Take care, most actions done on this page cannot be reversed.</b></p>
			</div>
			<div>
				<p>Registered accounts listed below:</p>
				<table>
					<tr>
						<td>Username</td>
						<td>Account type</td>
						<td>Activation status</td>
						<td></td>
					</tr>
						<?php
						$i = 0;
						while ($i < $accountcount){
							echo "<tr><td>";
							echo($accountdata[$i]['username']);
							echo("</td>");
							echo("<td>");
							if ($accountdata[$i]['account_type'] != 1){
								echo("normal");
							} else {
								echo("admin");
							}
							echo("</td>");
							echo("<td>");
							if ($accountdata[$i]['activation_code'] != '1'){
								echo("Not activated");
							} else {
								echo("Activated");
							}
							echo("</td>");
							echo("<td>");
							echo('<a href="removeaccount.php?account='.$accountdata[$i]['username'].'" onclick="return confirm(\'Are you sure, you want to delete the account?\')"class="button6">Remove account</a>');
							echo("</td>");
							$i++;	
						}
						?>					
				</table>
			</div>
			<div>
				<p>All traps listed below:</p>
				<table>
					<tr>
						<td>Trap code</td>
						<td>Latitude</td>
						<td>Longitude</td>
						<td>Amount of fish</td>
						<td></td>
					</tr>
						<?php
						$i = 0;
						while ($i < $trapcount){
							echo "<tr><td>";
							echo($trapdata[$i]['trap_code']);
							echo("</td>");
							echo("<td>");
							echo($trapdata[$i]['latitude']);
							echo("</td>");
							echo("<td>");
							echo($trapdata[$i]['longitude']);
							echo("</td>");
							echo("<td>");
							echo($trapdata[$i]['fishamount']);
							echo("</td>");
							echo("<td>");
							// Var in the HTML get defines if the script returns on this page or to settings
							echo('<a href="removetrap.php?trap_id='.$trapdata[$i]['trap_code'].'&var=1" onclick="return confirm(\'Are you sure, you want to delete it?\')"class="button6">Remove trap</a>');
							echo("</td>");
							$i++;
						}
						?>					
				</table>
			</div>
			<div>
				<p>Fish data history:</p>
				<p class="buttons">
					<button class="button" onclick="exportData()" >
						Export fish data
					</button>
					<a href="deletehistory.php" onclick="return confirm('Are you sure, you want to delete the data, this cannot be reversed?')"class="buttonred">Empty fish history</a>
				</p>
				<table id="tblfish" cellpadding="0" cellspacing="0">
					<tr>
						<td>N</td>
						<td>Length</td>
						<td>Time</td>
						<td>Direction</td>
						<td>Trap</td>
						<td>Latitude</td>
						<td>Longitude</td>
					</tr>
						<?php
						$i = 0;
						while ($i < $fishcount){
							echo "<tr><td>";
							echo($i+1);
							echo("</td>");
							echo("<td>");
							echo($fishdata[$i]['length']);
							echo("</td>");
							echo("<td>");
							echo($fishdata[$i]['time']);
							echo("</td>");
							echo("<td>");
							echo($fishdata[$i]['direction']);
							echo("</td>");
							echo("<td>");
							echo($fishdata[$i]['trap_code']);
							echo("</td>");
							echo("<td>");
							echo($fishdata[$i]['latitude']);
							echo("</td>");
							echo("<td>");
							echo($fishdata[$i]['longitude']);
							echo("</td>");
							$i++;
						}
						?>					
				</table>
			</div>
		</div>
	</body>
</html>
