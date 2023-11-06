<?php


declare(strict_types=1);

#Checks signup errors by looking at the $errors array and seeing if there are any values returned. If there are not, then it will return success if the index.php file === success.
function check_ca_errors()
{
    if (isset($_SESSION['errors_create_auction'])) {
        $errors = $_SESSION('errors_create_auction');

        echo "<br>";

        foreach ($errors as $error) {
            echo '<p class = "form-error">' . $error . '</p>';

        }

        unset($_SESSION['errors_create_auction']);
    } else if (isset($_GET["create_auction"]) && $_GET["create_auction"] === "success") {
        echo '<br>';
        echo "<p class='form-success'> Signup success! </p>";
    }
}