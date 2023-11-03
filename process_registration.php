<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_name = 'auctionDataBase';

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accountType = isset($_POST["accountType"]) ? $_POST["accountType"] : null;
    $username = isset($_POST["username"]) ? $_POST["username"] : null;
    $firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : null;
    $lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;
    $address = isset($_POST["address"]) ? $_POST["address"] : null;


    try {
        $conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
        require_once getcwd()."\includes\signup_model.inc.php";
        require_once getcwd()."\includes\signup_contr.inc.php";

        // ERROR HANDLERS
        $errors = [];

        if (is_input_empty($accountType, $username, $password, $firstname, $lastname, $email, $address)) {
            $errors["empty_input"] = "Fill in all fields";
        }
        if (is_email_invalid($email)) {
            $errors["invalid_email"] = "Invalid email address";
        }
        if (is_username_taken($conn, $username)) {
            $errors["username_taken"] = "Username already taken";
        }
        if (is_email_already_registered($conn, $email)) {
            $errors["email_used"] = "Email already registered";
        }

        require_once 'header.php';

        if ($errors) {
            $_SESSION["errors_signup"] = $errors;
            header("Location: ../sign_up_system.php");
            die();
        }
        create_user($conn, $accountType, $username, $password, $firstname, $lastname, $email, $address);
        header("Location: ../index.php?signup=success");
        mysqli_close($conn);
        $stmt = null;


    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../register.php");
    die();
}
?>

