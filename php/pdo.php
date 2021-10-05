<?php
require_once "config.php";
// This needs different approach
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=phplogin',
    $dbUsername, $dbPassword);
// See the "errors" folder for details...
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>