<?php
require_once "config_database.php";
include_once("header.php");
ob_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accountType = isset($_POST["accountType"]) ? $_POST["accountType"] : null;
    $username = isset($_POST["username"]) ? $_POST["username"] : null;
    $firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : null;
    $lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;
    $address = isset($_POST["address"]) ? $_POST["address"] : null;
    
    $conn = connect_to_database();
    $username = mysqli_real_escape_string($conn, $username);
    $firstname = mysqli_real_escape_string($conn, $firstname);
    $lastname = mysqli_real_escape_string($conn, $lastname);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    $address = mysqli_real_escape_string($conn, $address);

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
                echo "<div style='text-align: center;'><br><h5>Error: " . implode(', ', $errors)."</h5></div>";
                header("refresh:5;url=../register.php");
                die();
            }
            create_user($conn, $accountType, $username, $password, $firstname, $lastname, $email, $address);
            echo "<div style='text-align: center;'><br><h5>Account created successfully!</h5></div>";
            header("refresh:5;url=../browse.php");
            mysqli_close($conn);
            die();

        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }

}
else {
        header("Location: ../register.php");
        die();
    }
?>

