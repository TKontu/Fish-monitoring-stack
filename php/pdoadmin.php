<?php
require_once "config.php";
// This needs different approach
$pdoadmin = new PDO('mysql:host=localhost;port=3306;dbname=fishadmin',
    $dbUsername, $dbPassword);
/*
$pdoadmin = new PDO('mysql:host=localhost;port=3306;dbname=fishadmin',
    'kalastaja', 'Uzy9vJuVaSj6');
    */
// See the "errors" folder for details...
$pdoadmin->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>