<?php
require_once "pdo.php";
require_once "pdoadmin.php";
require_once "pdotraps.php";
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit;
}

echo('Preparing to remove trap.');
$traptoberemoved = htmlentities($_GET['trap_id']);
echo('<br>');
echo('Trap to be removed:'.$traptoberemoved);
echo('<br>');


//Prior removing the trap the fish data needs to be transferred to Luke database
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



//To remove the trap from system we need to load user's settings:
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


$i = 0;
$j = 0;

while ($i < $trapcount) {
	echo($trapsarray[$i]);
	echo(',');
	if ($trapsarray[$i] === $traptoberemoved) {
		array_splice($nicknamesarray,$i,1);
		$i++;
		continue;
	} else {
		$updatedtrapsarray[$j] = $trapsarray[$i];
		$j++; 
	}
	$i++;
}

$trapcount--;
echo('<br>');
echo('<br>');
$newtrapsstring = "";

$j = 0;
while ($j < $trapcount) {
	echo($updatedtrapsarray[$j]);
	echo(',');
	$newtrapsstring .= $updatedtrapsarray[$j];
	if ($j < ($trapcount - 1)) {
		$newtrapsstring .= ',';
	}
	$j++;
}

echo('<br>');
echo('<br>');
echo($newtrapsstring);

echo('<br>');
echo('updating usersettings');

$sql = "UPDATE usersettings SET traps = :traps, trapcount = :trapcount, nicknames = :nicknames WHERE user_id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
            ':traps' => $newtrapsstring,
            ':trapcount' => $trapcount,
			':nicknames' => serialize($nicknamesarray),
			':id' => $_SESSION['id']));

echo('<br>');
echo('Dropping trap table');

$sql = "DROP TABLE  $traptoberemoved ";
			
$stmt = $pdotraps->prepare($sql);
$stmt->execute(array(
	));


//Trap row to be deleted from trapmetadata:
$sql = "DELETE FROM trapmetadata WHERE trap_code = :trap_id;";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
			':trap_id' => $traptoberemoved));

if (htmlentities($_GET['var'])==1){
	$_SESSION["greeting"] = "Trap removed";
	header('Location: administration.php');	
} else {
	$_SESSION["greeting"] = "Trap removed";
	header('Location: settings.php');
}

?>