<?php
/*
// TODO: Extract $_POST variables, check they're OK, and attempt to login.
*/
session_start();
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_name = 'auctionDataBase';

global $conn;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;
}

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (mysqli_connect_errno()){
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Check if data from login form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = $_POST['email'];
    $password = $_POST['password'];
}

// Start the authenticating process of the inputted email and password
if ($stmt = $conn->prepare('SELECT password, accounttype, id FROM Users WHERE email = ?')) {
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if the email is within the Users table
    if ($stmt->num_rows <= 0) {
        echo 'Email or password Incorrect!';
        header("refresh:5;url=index.php");
    } else {
        $stmt->bind_result($password, $accounttype, $userid);
        $stmt->fetch();

        if (password_verify($_POST['password'], $password)) {
            session_regenerate_id();
            $_SESSION['logged_in'] = true;
            $_SESSION['account_type'] = $accounttype;
            $_SESSION['id'] = $userid;
            // Access the accounttype
            echo 'You are now logged in with account type: ' . $accounttype;
            echo('<div class="text-center">You will be redirected shortly.</div>');
            header("refresh:5;url=index.php");
        } else {
            echo 'Email or password incorrect!';
            header("refresh:3;url=index.php");
        }
    }
    $stmt->close();
}

?>