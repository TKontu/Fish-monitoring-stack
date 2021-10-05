<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit;
}
require_once "pdo.php";

echo($_GET["longitude"]);
echo('<br>');
echo($_GET["trapid"]);

$sql = "UPDATE trapmetadata SET longitude = :longitude WHERE trap_code = :trapid";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
			':longitude' => htmlentities($_GET["longitude"]),
			':trapid' => $_GET["trapid"]));

$_SESSION['greeting'] = "Longitude information added";
			
header('Location: settings.php');
			
?>