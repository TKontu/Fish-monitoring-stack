<?php
// We need to use sessions, so you should always start sessions using the below code.
require_once "pdo.php";
session_start();

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['recoverycode']) ) {
	// Could not get the data that should have been sent.
    $_SESSION['error'] = "Please fill all fields!";
    header('Location: ../recovery.php');
} else {
    echo("Handling POST data...\n");
    $sql = "SELECT recovery_code FROM accounts WHERE username = :un";
    //echo "<pre>\n$sql\n</pre>\n";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':un' => htmlentities($_POST['username'])));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //Let's verify that such account exists:
    if ($row !== false) {
        if (password_verify(htmlentities($_POST['recoverycode']), $row['recovery_code'])) {
            session_regenerate_id();
            $_SESSION['verified'] = TRUE;
            $_SESSION['username'] = htmlentities($_POST['username']);
            header('Location: ../password.php');
        } else {
            // Incorrect password
            $_SESSION['error'] = "Incorrect username and/or recovery code!";
            header('Location: ../recovery.php');
        }
    } else {
        // Incorrect username
        $_SESSION['error'] = "Incorrect username and/or recovery code!";
        header('Location: ../recovery.php');
    }

    $pdo->connection = null;


}
?>
