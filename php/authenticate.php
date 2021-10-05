<?php
session_start();
require_once "pdo.php";

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('Please fill both the username and password fields!');
} else {
    echo("Handling POST data...\n");
    $sql = "SELECT id, password, activation_code, account_type FROM accounts WHERE username = :un";
    echo "<pre>\n$sql\n</pre>\n";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':un' => htmlentities($_POST['username'])));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //Let's verify that such account exists:
    if ($row !== false) {
        if ($row['activation_code'] !== '1'){
            // Account not activated
            $_SESSION['error'] = "You account has not been activated yet!";
            header('Location: ../index.php');
            exit;
        }
        if (password_verify($_POST['password'], $row['password'])) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = htmlentities($_POST['username']);
            $_SESSION['id'] = $row['id'];
            $_SESSION['accounttype'] = $row['account_type']; //0 for standard account; 1 for admin account
            $_SESSION['welcome'] = TRUE;
            header('Location: home.php');
        } else {
            // Incorrect password
            $_SESSION['error'] = "Incorrect username and/or password!";
            header('Location: ../index.php');
        }
    } else {
        // Incorrect username
        $_SESSION['error'] = "Incorrect username and/or password!";
        header('Location: ../index.php');
    }

    $pdo->connection = null;


}
?>

