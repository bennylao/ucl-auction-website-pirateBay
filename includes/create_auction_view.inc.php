<?php


declare(strict_types=1);

#Checks signup errors by looking at the $errors array and seeing if there are any values returned. If there are not, then it will return success if the index.php file === success.
function check_signup_errors()
{
    if (isset($_SESSION['errors_signup'])) {
        $errors = $_SESSION('errors_signup');

        echo "<br>";

        foreach ($errors as $error) {
            echo '<p class = "form-error">' . $error . '</p>';

        }

        unset($_SESSION['errors_signup']);
    } else if (isset($_GET["signup"]) && $_GET["signup"] === "success") {
        echo '<br>';
        echo "<p class='form-success'> Signup success! </p>";
    }

}