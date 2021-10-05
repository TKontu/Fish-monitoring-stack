<?php
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


//To remove the account from system we need to load users ID from account table:
$sql = "SELECT id FROM accounts WHERE username = :un";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':un' => $_GET['account']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

			$id = $row['id'];

//Account row to be deleted from accounts:
$sql = "DELETE FROM accounts WHERE id = :id;";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
			':id' => $id));

//Account row to be deleted from usersettings:
$sql = "DELETE FROM usersettings WHERE user_id = :id;";

			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
			':id' => $id));


$_SESSION["greeting"] = $_GET['account']." removed";
header('Location: administration.php');	




?>