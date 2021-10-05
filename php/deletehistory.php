<?php
require_once "pdoadmin.php";
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit;
}
if ($_SESSION['accounttype'] == 0) {
	header('Location: home.php');
	exit;
}

$sql = "DELETE FROM fishhistory";

            $stmt = $pdoadmin->prepare($sql);
            $stmt->execute(array(
			    ));

$_SESSION['greeting'] = "History deleted";

header('Location: administration.php');

?>