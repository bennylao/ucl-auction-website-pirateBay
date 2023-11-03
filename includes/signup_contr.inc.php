<?php

declare(strict_types=1);

function is_input_empty(string $accountType, string $username, string $password,string $firstname, string $lastname, string $email, string $address){
    if (empty($accountType) || empty($username) || empty($password) || empty($firstname) || empty($lastname) || empty($email) || empty($address)) {
     return true;
    } else {
        return false;
    }
}

function is_email_invalid(string $email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function is_username_taken(object $conn, string $username) {
    if (get_username($conn, $username)) {
        return true;
    } else {
        return false;
    }
}

function is_email_already_registered(object $conn, string $email) {
    if (get_email($conn, $email)) {
        return true;
    } else {
        return false;
    }
}
function create_user(object $conn, string $accountType, string $username, string $password,string $firstname, string $lastname, string $email, string $address) {
    set_user($conn, $accountType, $username, $password, $firstname, $lastname, $email, $address);
}
