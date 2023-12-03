<?php
require_once("config_database.php");
/*
// TODO: Extract $_POST variables, check they're OK, and attempt to login.
*/
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // get variables from the POST method
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $inputPassword = isset($_POST["password"]) ? $_POST["password"] : null;

    $conn = connect_to_database();
    $email = mysqli_real_escape_string($conn, $email);
    $inputPassword = mysqli_real_escape_string($conn, $inputPassword);

    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    $query = "SELECT password, accountType, userId, userName FROM users WHERE email = '$email';";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $password = $row['password'];
        if (password_verify($inputPassword, $password)) {
            $userId = $row['userId'];
            $accountTypeId = $row['accountType'];
            $userName = $row['userName'];
            $accountResult = mysqli_query($conn, "SELECT accountType FROM accountTypes WHERE typeId = $accountTypeId;");
            $accountType = mysqli_fetch_assoc($accountResult)["accountType"];

            session_regenerate_id();
            $_SESSION['logged_in'] = true;
            $_SESSION['account_type'] = $accountType;
            $_SESSION['id'] = $userId;
            $_SESSION['user_name'] = $userName;

            echo 'You are now logged in with account type: ' . $accountType;
            echo('<div class="text-center">You will be redirected shortly.</div>');
            header("refresh:5;url=index.php");
        } else {
            echo 'Email or password incorrect!';
            header("refresh:5;url=index.php");
        }
    } else {
        echo 'Email does not exist!';
        header("refresh:5;url=index.php");
    }
}
?>