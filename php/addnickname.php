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

echo($_GET["nickname"]);
echo('<br>');
echo($_GET["trapid"]);

function isnicknamed($pdo, $id) {
	try {
		$sql = "SELECT nicknames FROM usersettings WHERE user_id = :id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':id' => $id));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$nicknames = $row['nicknames'];
				$nicknamesarray = unserialize($nicknames);
	} catch (Exception $e) {
		// We got an exception == table not found
		return FALSE;
	}
    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $nicknamesarray;
}

$sql = "SELECT trapcount, traps FROM usersettings WHERE user_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':id' => $_SESSION['id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

			$trapcount = $row['trapcount'];
			$traps = $row['traps'];
			$trapsarray = explode(",", $traps);

//let's locate array key:
echo('<br>');
echo('lets search array key');
echo('<br>');
$key = array_search($_GET["trapid"], $trapsarray); // $key = 2;
echo($key);


if (!isnicknamed($pdo, $_SESSION['id'])) {
	echo('<br>');
	echo('Nickname array doesnt exist');
	echo('<br>');
	echo('Generating new array');
	$nicknamesarray = array($key => $_GET["nickname"],);
	echo('<br>');
	var_dump($nicknamesarray);
} else {
	echo('<br>');
	echo('table exists, lets edit it');
	$nicknamesarray = isnicknamed($pdo, $_SESSION['id']);
	echo('<br>');
	var_dump($nicknamesarray);
	$nicknamesarray[$key] = $_GET["nickname"];
	echo('<br>');
	var_dump($nicknamesarray);
}

$sql = "UPDATE usersettings SET nicknames = :nicknames WHERE user_id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
			':nicknames' => serialize($nicknamesarray),
			':id' => $_SESSION['id']));

$_SESSION['greeting'] = "Nickname added";
			
header('Location: settings.php');
			
?>