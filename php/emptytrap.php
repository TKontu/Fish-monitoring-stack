<?php
require_once "pdotraps.php";
require_once "pdoadmin.php";
require_once "pdo.php";
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit;
}

//Prior emptying the trap the fish data needs to be transferred to Luke database
//Lets get trap metadata
$sql = "SELECT latitude, longitude FROM trapmetadata WHERE trap_code = :trap_code";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':trap_code' => htmlentities($_GET['trap_id'])));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

$latitude = $row['latitude'];
$longitude = $row['longitude'];

$dir = "in";

$stmt = $pdotraps->query("SELECT * FROM ".htmlentities($_GET['trap_id'].""));
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$amount = 0;
	
	foreach ( $rows as $row ) {
		$sql = "INSERT INTO fishhistory (length, time, direction, trap_code, latitude, longitude) VALUES (:length, :time, :direction, :trap_code, :latitude, :longitude)";
            
            $stmt = $pdoadmin->prepare($sql);
            $stmt->execute(array(           
			':length' => $row['length'],
			':time' => $row['time'],
			':direction' => $dir,
			':trap_code' => htmlentities($_GET['trap_id']),
			':latitude' => $latitude,
			':longitude' => $longitude));
	}


//Emptying trap:
echo('Preparing to empty trap.');
$traptobeemptied = htmlentities($_GET['trap_id']);
echo('<br>');
echo('Trap to be emptied:'.$traptobeemptied);
echo('<br>');

$sql = "DELETE FROM  $traptobeemptied ";
			
$stmt = $pdotraps->prepare($sql);
$stmt->execute(array(
	));


//Add a timestamp on the emptytimes cell in database phplogin table trapmetadata:

$date = new DateTime("now", new DateTimeZone('Europe/Helsinki') );
$result = $date->format('Y-m-d H:i:s');
var_dump($result);

$sql = "UPDATE trapmetadata SET trap_emptied = :trap_emptied WHERE trap_code = :trapid";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
			':trap_emptied' => $result,
			':trapid' => $_GET["trap_id"]));

$_SESSION['greeting'] = "Trap emptied";


header('Location: home.php');

?>