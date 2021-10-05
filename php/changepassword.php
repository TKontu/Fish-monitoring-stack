<?php
require_once "pdo.php";
// We need to use sessions, so you should always start sessions using the below code.
session_start();

$currentpassword = htmlentities($_POST['currentpassword']);
$newpassword = htmlentities($_POST['newpassword']);
$reenteredpassword = htmlentities($_POST['reenteredpassword']);

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin']) && !isset($_SESSION['verified'])) {
    $_SESSION['error'] = "Not authenticated/Session timeout!";
	header('Location: ../index.php');
    exit;
    
} elseif (isset($_SESSION['loggedin'])) {
    echo("Handling POST data...\n");
    $sql = "SELECT password FROM accounts WHERE id = :id";
    echo "<pre>\n$sql\n</pre>\n";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':id' => $_SESSION['id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //Enter old password:
    if (password_verify($currentpassword, $row['password'])) {
        //If passwords match:
        if ($newpassword === $reenteredpassword) {
            if (strlen($newpassword) > 20 || strlen($newpassword) < 5) {
                $_SESSION['error'] = "The new password must be between 5 and 20 characters long!";
                header('Location: profile.php');
            } else {

                $sql = "UPDATE accounts SET password = :password WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                ':password' => password_hash(htmlentities($_POST['newpassword']), PASSWORD_DEFAULT),
                ':id' => $_SESSION['id']));      

                //Everything done incorrectly post a welcome message:
                $_SESSION['greeting'] = "Password changed successfully!";
                header('Location: profile.php');
            }
        } else {
            // New passwords entered incorrectly
            $_SESSION['error'] = "New passwords do not match!";
            header('Location: profile.php');
        }
        
    } else {
        // Incorrect password
        $_SESSION['error'] = "Old password entered incorrectly!";
        header('Location: profile.php');
    }

    $pdo->connection = null;

} elseif (isset($_SESSION['verified'])) {

    if ($newpassword === $reenteredpassword) {
        if (strlen($newpassword) > 20 || strlen($newpassword) < 5) {
            $_SESSION['error'] = "The new password must be between 5 and 20 characters long!";
            header('Location: ../password.php');
        } else {

            $sql = "UPDATE accounts SET password = :password WHERE username = :un";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
            ':password' => password_hash(htmlentities($_POST['newpassword']), PASSWORD_DEFAULT),
            ':un' => $_SESSION['username']));      

            //Everything done incorrectly post a welcome message:
            $_SESSION['greeting'] = "Password changed successfully!";
            header('Location: ../index.php');
        }
    } else {
        // New passwords entered incorrectly
        $_SESSION['error'] = "New passwords do not match!";
        header('Location: ../password.php');
    }
        
    

    $pdo->connection = null;

}


?>