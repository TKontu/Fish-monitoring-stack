<?php
// We need to use sessions, so you should always start sessions using the below code.
require_once "pdo.php";
session_start();

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['activationcode']) ) {
	// Could not get the data that should have been sent.
    $_SESSION['error'] = "Please fill all fields!";
    header('Location: ../activate.php');
} else {
    echo("Handling POST data...\n");
    $sql = "SELECT activation_code FROM accounts WHERE username = :un";
    //echo "<pre>\n$sql\n</pre>\n";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':un' => htmlentities($_POST['username'])));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //Let's verify that such account exists:
    if ($row !== false) {
		if ($row['activation_code'] == 'activated'){
			$_SESSION['greeting'] = "Your account was already activated";
            header('Location: ../index.php');
            exit;	
		}
        elseif (password_verify(htmlentities($_POST['activationcode']), $row['activation_code'])) {
			$sql = "UPDATE accounts SET activation_code = :activated WHERE username = :un AND activation_code = :ac";
				//echo "<pre>\n$sql\n</pre>\n";
				$newcode = '1';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
                    ':activated' => $newcode,
                    ':ac' => $row['activation_code'],
                    ':un' => htmlentities($_POST['username'])));

            $sql = "UPDATE accounts SET email = :email WHERE username = :un";
                //echo "<pre>\n$sql\n</pre>\n";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':email' => '',
                    ':un' => htmlentities($_POST['username'])));        

				$_SESSION['greeting'] = "Your account has been activated, you can login!";
                header('Location: ../index.php');
                exit;
        } else {
            // Incorrect activation code
			$_SESSION['error'] = "Incorrect username and/or <b>activation code</b>!";
			$pwd = password_hash(htmlentities($_POST['activationcode']), PASSWORD_DEFAULT);
			//echo('<br>');
			//echo($pwd);
			//echo('<br>');
			//echo($row['activation_code']);
            header('Location: ../activate.php');
            exit;
        }
    } else {
        // Incorrect username
        $_SESSION['error'] = "Incorrect <b>username</b> and/or activation code!";
        header('Location: ../activate.php');
        exit;
    }

    $pdo->connection = null;


}
?>

