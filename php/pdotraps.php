<?php
require_once "config.php";
// This needs different approach
$pdotraps = new PDO('mysql:host=localhost;port=3306;dbname=traps',
    $dbUsername, $dbPassword);
// See the "errors" folder for details...
$pdotraps->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>