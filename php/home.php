<?php
require_once "pdo.php";
require_once "pdotraps.php";
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit;
}
//First user account's settings need to be identified, based on this the layout is modified

$sql = "SELECT trapcount, traps, nicknames FROM usersettings WHERE user_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':id' => $_SESSION['id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

			$trapcount = $row['trapcount'];
			$traps = $row['traps'];
			$trapsarray = explode(",", $traps);
			$nicknames = $row['nicknames'];
			$nicknamesarray = unserialize($nicknames);


//Now we've identified how many monitored traps the user has within variable $trapcount
//And we've saved the trap identifiers within array $trapsarray; $trapsarray[0]...$trapsarray[n...]

//Following code saves the data from each trap within arrays, eats bin within array represents info from one trap:
$i = 0;
while ($i < $trapcount) {

	$stmt = $pdotraps->query("SELECT * FROM ".$trapsarray[$i]."");
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$amount[$i] = 0;
	$amountlarge[$i] = 0;
	$amountsmall[$i] = 0;
	$chosendivider = 45;
	$totallength[$i] = 0;
	$averagelength[$i] = 0; 
	
	foreach ( $rows as $row ) {
		$amount[$i]++;
		$totallength[$i] += $row['length'];
		if ($row['length'] >= $chosendivider){
			$amountlarge[$i]++;
		} else {
			$amountsmall[$i]++;
		}
	}
	if (!$amount[$i] == 0){
		$averagelength[$i] = round(($totallength[$i] / $amount[$i]),2);
	} else {
		$averagelength[$i] = 0;
	}

	$latestcatchtime[$i] = "";
	$latestfishlength[$i] = 0;

	$sql = "SELECT * FROM ".$trapsarray[$i]." WHERE TIMESTAMP(time) = ( SELECT MAX((time)) FROM ".$trapsarray[$i]." )";
            $stmt = $pdotraps->prepare($sql);
            $stmt->execute(array(
                ));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($row !== false){
				$latestcatchtime[$i] = $row['time'];
				$latestfishlength[$i] = $row['length'];
			}
			
	$i++;
} 

$i = 0;
while ($i < $trapcount) {
	$sql = "SELECT trap_emptied FROM trapmetadata WHERE trap_code = :trap_code";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':trap_code' => $trapsarray[$i]));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				$emptytimes[$i] = $row['trap_emptied'];

				$i++;


}

?>



<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
        <link href=".././css/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Kalansat.fi</h1>
				<?php
					if ($_SESSION['accounttype'] == 1) {
						echo('<a href="administration.php"><i class="fas fa-users-cog"></i>Administration</a>');
					}					
				?>
				<a href="profile.php"><i class="fas fa-user-cog"></i>Profile</a>
				<a href="settings.php"><i class="fas fa-cogs"></i>Settings</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>
				Home Page
			</h2>
			<?php
				if (isset($_SESSION['welcome']) ) {
					echo('<p style="color:green">Logged in!');
					echo('<br>');
					echo('<span style="color:black;">');
					echo('Welcome back, '.$_SESSION['name'].'!');
					echo('</span></p>');
				}
				unset($_SESSION["welcome"]);

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
		</div>
		<section class="page-content">
			<section class="grid">
				<?php
					//below code makes a separate trap view "article" for each trap defined for user
					$i = 0;
					while ($i < $trapcount) {
						echo('<article>');
							echo('<div id="trap-view">');
								echo('<div id="trap-info-view"><b>Trap ID: </b>');
								if ($nicknamesarray !== FALSE){
									//var_dump($emptytimesarray);
									if (array_key_exists($i,$nicknamesarray)){
										echo($nicknamesarray[$i]);
									} else {
										echo($trapsarray[$i]);
									}
								} else {
									echo($trapsarray[$i]);
								}
								echo('</div>');
								echo('<div id="trap-box-layout">');
									echo('<div id="trap-details-view">');
										echo('<div id="text-group-view">');
											echo('<div id="total-amount-view"><b>Total amount: </b>'.$amount[$i].'</div>');
											echo('<div id="average-length-view"><b>Average length: </b>'.$averagelength[$i].' cm</div>');
										echo('</div>');
										echo('<div id="text-group-view">');
											echo('<div id="amount-large-view"><b>Amount large: </b>'.$amountlarge[$i].'</div>');
											echo('<div id="amount-small-view"><b>Amount small: </b>'.$amountsmall[$i].'</div>');
										echo('</div>');
									echo('</div>');
									echo('<div id="emptytrap-button">');
										echo('<a href="emptytrap.php?trap_id='.$trapsarray[$i].'" onclick="return confirm(\'Are you sure, you want empty this trap?\')"class="button6">Empty trap</a>');
									echo('</div>');
								echo('</div>');
								echo('<div id="trap-times">');
									echo '<table id="table01">';
									echo "<tr><td>";
									echo('<b>Latest fish caught: </b>'.$latestcatchtime[$i]);
									echo("</td><td>");
									echo('<b>Latest fish size: </b>'.$latestfishlength[$i]." cm");
									echo("</td>");
									echo "</tr><tr><td>";
									echo('<b>Trap emptied:    </b>');
									if ($emptytimes !== FALSE){
										//var_dump($emptytimesarray);
										if (array_key_exists($i,$emptytimes)){
											echo($emptytimes[$i]);
										}
									}
									echo "</td></tr>";
									echo "</table>";
								echo('</div>');
							echo('</div>');
						echo('</article>');
						$i++;
					}
				?>
			</section>
			</section>
	</body>
</html>