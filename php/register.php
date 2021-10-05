<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../phpmailer/Exception.php';
require_once "pdo.php";
require_once "config.php";

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

session_start();

function uniqidReal($lenght = 13) {
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
}
// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    // Could not get the data that should have been sent.
	$_SESSION['error'] = "Please fill all fields!";
    header('Location: ../registerscreen.php');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
    // One or more values are empty.
	$_SESSION['error'] = "Please fill all fields!";
    header('Location: ../registerscreen.php');
} else {
    $sql = "SELECT id FROM accounts WHERE username = :un";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':un' => htmlentities($_POST['username'])));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (isset($row['id'])) {
        // Username already exists
		$_SESSION['error'] = "Username exists, choose another!";
        header('Location: ../registerscreen.php');
    } else {
        // Username doesnt exists, insert new account
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Email not acceptable!";
            header('Location: ../registerscreen.php');
            exit;
        }
        if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
            $_SESSION['error'] = "Username not acceptable!";
            header('Location: ../registerscreen.php');
            exit;
        }
        if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
            $_SESSION['error'] = "Password must be between 5 and 20 characters long!";
            header('Location: ../registerscreen.php');
            exit;
        } else {
            $sql = "INSERT INTO accounts (username, password, recovery_code, activation_code) VALUES (:name, :password, :recode, :code)";

                $password = password_hash(htmlentities($_POST['password']), PASSWORD_DEFAULT);
                $uniqid = uniqidReal();
                $hashuniqid = password_hash($uniqid, PASSWORD_DEFAULT);
                $uniqrecoverycode = uniqidReal();
                $hashreccode = password_hash($uniqrecoverycode, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                ':name' => htmlentities($_POST['username']), //html entities used to prevent php injection.
                ':password' => $password,
                ':recode' => $hashreccode,            
                ':code' => $hashuniqid));

            // Functionality for individual user settings below:

                //get the newly created user's ID:
            $sql = "SELECT id FROM accounts WHERE username = :name";

                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':name' => htmlentities($_POST['username'])));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //Store the ID to a variable:
            $idhandler = $row['id'];

            //Add a new row to usersettings table:

            $sql = "INSERT INTO usersettings (user_id) VALUES (:id)";
            
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(           
                ':id' => $idhandler));

            //Activation code sending below:
            
            //echo("Milestone");
            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = "smtp.gmail.com";                       // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = $mailUsername;                     // SMTP username
                $mail->Password   = $mailPassword;                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            
                //Recipients
                $mail->setFrom('from@example.com', 'Kalansat.fi');
                $mail->addAddress(htmlentities($_POST['email']));               // Name is optional
                
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Kalansat account activation info';
                $mail->Body    = '<p>
                                Thanks for registering to the service!
                                <br>
                                <br>
                                Your username is:   <b>'.htmlentities($_POST['username']).'</b>
                                <br> 
                                Before you can sign in to your account you need to activate it by using the below activation code on the activation page.
                                <br>
                                <br>
                                Activation code:  <b>'.$uniqid.'</b>
                                <br>
                                Please take care and save this message. If you happen to lose your password, using the below recovery code is only way to recover your account.
                                <br>
                                <br>
                                Recovery code:  <b>'.$uniqrecoverycode.'</b>
                                <br>
                                <br>
                                Hope to see you soon on the service!
                                </p>';
                $mail->AltBody = 
'Thanks for registering to the service!
Your username is:   '.htmlentities($_POST['username']).'
Before you can sign in to your account you need to activate it by using the below activation code on the activation page.
Activation code:  '.$uniqid.'
Please take care and save this message. If you happen to lose your password, using below recovery code is the only way to recover your account.
Recovery code:  '.$uniqrecoverycode.'
Hope to see you soon on the service!';
                $_SESSION['greeting'] = "Please check your email to activate your account!";
                $mail->send();
                echo "Mail sent! Please activate your account.";
                $_SESSION['greeting'] = "Please check your email to activate your account!";
                header('Location: ../registerscreen.php');
            } catch (Exception $e) {
                error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
                $_SESSION['error'] = "Problem with mail server, contact system owner!";
                header('Location: ../index.php');
            }
            
            
        }

    }

}

$pdo->connection = null;


?>
