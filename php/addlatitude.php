<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit;
}
require_once "pdo.php";

echo($_GET["latitude"]);
echo('<br>');
echo($_GET["trapid"]);

$sql = "UPDATE trapmetadata SET latitude = :latitude WHERE trap_code = :trapid";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
			':latitude' => htmlentities($_GET["latitude"]),
			':trapid' => $_GET["trapid"]));

$_SESSION['greeting'] = "Latitude information added";
			
header('Location: settings.php');
			
?>