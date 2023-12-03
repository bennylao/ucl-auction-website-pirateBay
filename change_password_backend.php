<?php
require_once "config_database.php";
require_once("utilities.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $conn = connect_to_database();
    $currentPw = mysqli_real_escape_string($conn, $_POST['currentPw']);
    $newPw = mysqli_real_escape_string($conn, $_POST['newPw']);
    $confirmPw = mysqli_real_escape_string($conn, $_POST['confirmPw']);
}

if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
session_start();
$userId = $_SESSION['id'];

if(!empty($currentPw) && !empty($newPw) && !empty($confirmPw)){
    $query = "SELECT password FROM users WHERE userId = $userId;";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $password = $row['password'];
        if (password_verify($currentPw, $password)) {
            if ($newPw == $confirmPw){
                $options = ['cost' => 12];
                $hashed_password = password_hash($newPw, PASSWORD_BCRYPT, $options);
                $query = "UPDATE users SET password = '$hashed_password' WHERE userId = $userId;";
                $result = mysqli_query($conn, $query);
                if($result) {
                    echo 'Password updated';
                    header("refresh:2;user_homepage.php");
                } else {
                    echo 'Something is wrong, please try again';
                    header("refresh:2;change_password.php");
                }
            } else {
                echo 'New password and confirm password must match';
                header("refresh:2;change_password.php");
            }
        } else {
            echo 'Password incorrect!';
            header("refresh:2;change_password.php");
        }
    } else {
        echo 'Invalid input';
        header("refresh:2;change_password.php");
    }
} else {
    echo 'Please fill in the blanks';
    header("refresh:2;change_password.php");
}
mysqli_close($conn);
?>