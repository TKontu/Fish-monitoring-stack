<?php
require_once "pdo.php";
require_once "pdotraps.php";
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit;
}

//function to generate random trapname string:
$n=6; 
function generateTrapId($n) { 
    $characters = 'abcdefghijklmnopqrstuvwxyz'; 
    $randomString = ''; 
  
    for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randomString .= $characters[$index]; 
    } 
  
    return $randomString; 
} 

// function to check if a table with a specific name exists already
/*
 * Check if a table exists in the current database.
 *
 * @param PDO $pdo PDO instance connected to a database.
 * @param string $table Table to search for.
 * @return bool TRUE if table exists, FALSE if no table found.
 */
//check if exists already, if not, continue:
function tableExists($pdotraps, $table) {

    // Try a select statement against the table
    // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
    try {
        $result = $pdotraps->query("SELECT 1 FROM $table LIMIT 1");
    } catch (Exception $e) {
        // We got an exception == table not found
        return FALSE;
    }

    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $result !== FALSE;
}

//create new random trapname string:
stringgeneration:
$newid = generateTrapId($n);
echo('new id generated: '.$newid);
echo('<br>');
echo('Checking if the id exists already.');
echo('<br>');

if (!tableExists($pdotraps, $newid)) {
	echo('<br>');
	echo('table does not exist');
	echo('<br>');
	echo('Generating table with name '.$newid);
	$sql = "CREATE TABLE $newid (
		id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
		length INTEGER NULL,
		time TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
		) ENGINE=InnoDB CHARSET=utf8";
             
	$stmt = $pdotraps->prepare($sql);
	$stmt->execute(array(
		));
} else {
	echo('<br>');
	echo('table exists, retrying');
	goto stringgeneration;
}

echo('<br>');
echo('Table added with name '.$newid);

//Newly created trap to be added to users settings
//First we need to load the existing trap list of the active user:
$sql = "SELECT trapcount, traps FROM usersettings WHERE user_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':id' => $_SESSION['id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

			$trapcount = $row['trapcount'];
			$traps = $row['traps'];
			$trapsarray = explode(",", $traps);

echo('<br>');
echo('users had '.$trapcount. ' traps');
echo('<br>');
echo('users old trap list: '.$traps);

//Add one trap to trapcount

$trapcount++;
echo('<br>');
echo('Now user has '.$trapcount. ' traps');

//Add new trap to end of string:

if (empty($traps)) {
	$traps = ($newid);
} else {
$tempstring = $traps;
$traps = ($tempstring.','.$newid);
}

echo('<br>');
echo('users new trap list: '.$traps);

//Add trap to session user ID's trap list:
$sql = "UPDATE usersettings SET traps = :traps, trapcount = :trapcount WHERE user_id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
            ':traps' => $traps,
            ':trapcount' => $trapcount,
            ':id' => $_SESSION['id']));

$sql = "INSERT INTO trapmetadata (trap_code) VALUES (:code)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(           
            ':code' => $newid));

header('Location: settings.php');
			
?>