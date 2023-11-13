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
    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    $query = "SELECT password, accountType, userId FROM users WHERE email = '$email';";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $password = $row['password'];
        if (password_verify($inputPassword, $password)) {
            $userId = $row['userId'];
            $accountTypeId = $row['accountType'];
            $accountResult = mysqli_query($conn, "SELECT accountType FROM accountTypes WHERE typeId = $accountTypeId;");
            $accountType = mysqli_fetch_assoc($accountResult)["accountType"];

            session_regenerate_id();
            $_SESSION['logged_in'] = true;
            $_SESSION['account_type'] = $accountType;
            $_SESSION['id'] = $userId;

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



//// Start the authenticating process of the inputted email and password
//if ($stmt = $conn->prepare('SELECT password, accountType, userId FROM Users WHERE email = ?')) {
//    $stmt->bind_param('s', $email);
//    $stmt->execute();
//    $stmt->store_result();
//
//    // Check if the email is within the Users table
//    if ($stmt->num_rows <= 0) {
//        echo 'Email or password Incorrect!';
//        header("refresh:5;url=index.php");
//    } else {
//        $stmt->bind_result($password, $accounttype, $userid);
//        $stmt->fetch();
//
//        if (password_verify($_POST['password'], $password)) {
//            session_regenerate_id();
//            $_SESSION['logged_in'] = true;
//            $_SESSION['account_type'] = $accounttype;
//            $_SESSION['id'] = $userid;
//            // Access the accounttype
//            echo 'You are now logged in with account type: ' . $accounttype;
//            echo('<div class="text-center">You will be redirected shortly.</div>');
//            header("refresh:5;url=index.php");
//        } else {
//            echo 'Email or password incorrect!';
//            header("refresh:3;url=index.php");
//        }
//    }
//    $stmt->close();
//}

?>