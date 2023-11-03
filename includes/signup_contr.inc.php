<?php

# The file responsible for containing the sign up functions that do not involve changing or interacting with the database.
declare(strict_types=1);

#Will return false if any of the input fields are not filled in.
function is_input_empty(string $accountType, string $username, string $password,string $firstname, string $lastname, string $email, string $address){
    if (empty($accountType) || empty($username) || empty($password) || empty($firstname) || empty($lastname) || empty($email) || empty($address)) {
     return true;
    } else {
        return false;
    }
}
#Will return false if the email is not valid, done using the in-built FILTER_VALIDATE_EMAIL Function
function is_email_invalid(string $email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

#Function that queries the database to see check if the username is taken - will return false if the username already exists.
function is_username_taken(object $conn, string $username) {
    if (get_username($conn, $username)) {
        return true;
    } else {
        return false;
    }
}

#Checks to ensure that only one email is currently registered with the account. Will return false if provided email is already registered.
function is_email_already_registered(object $conn, string $email) {
    if (get_email($conn, $email)) {
        return true;
    } else {
        return false;
    }
}
#Creates a user by taking in the database connection and the input variables and querying the database via the set_user function in the signup_model.inc.php file.
function create_user(object $conn, string $accountType, string $username, string $password,string $firstname, string $lastname, string $email, string $address) {
    set_user($conn, $accountType, $username, $password, $firstname, $lastname, $email, $address);
}
