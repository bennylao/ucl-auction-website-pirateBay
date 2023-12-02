<?php
require_once "config_database.php";
include_once("header.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accountType = isset($_POST["accountType"]) ? $_POST["accountType"] : null;
    $username = isset($_POST["username"]) ? $_POST["username"] : null;
    $firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : null;
    $lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;
    $address = isset($_POST["address"]) ? $_POST["address"] : null;
    
    $conn = connect_to_database();
    $existingEmailQuery = "SELECT * FROM users WHERE email = '$email';";
    $existingEmail = mysqli_query($conn, $existingEmailQuery);
    if (mysqli_num_rows($existingEmail) > 0) {
        mysqli_close($conn);
        echo "<div style='text-align: center;'><br><h5>'$email' already exists. Please use other email.";
        echo "<br><br><a href='register.php' class='btn btn-outline-info'>Return</a></div>";
    } else {


        try {

            require_once getcwd() . "/includes/signup_model.inc.php";
            require_once getcwd() . "/includes/signup_contr.inc.php";

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
                header("Location: ../register.php");
                die();
            }
            create_user($conn, $accountType, $username, $password, $firstname, $lastname, $email, $address);
            header("Location: ../index.php?signup=success");
            mysqli_close($conn);


        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
}
else {
        header("Location: ../register.php");
        die();
    }

?>

