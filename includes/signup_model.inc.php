<?php

declare(strict_types=1);


#Queries database to retrieve the provided username so to ensure it is not already taken.
function get_username(mysqli $conn, string $username) {
    $query = "SELECT username FROM auctionDatabase.Users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();
    return $result;
}

#Retrieves the email so that it can be fed into the checking email function in the control file.
function get_email(mysqli $conn, string $email) {
    $query = "SELECT username FROM auctionDatabase.Users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();
    return $result;
}

#Inserts the new user into the Users table within the AuctionDatabase, though it does not create a date as of yet.
function set_user(mysqli $conn, string $accountType, string $username, string $password, string $firstname, string $lastname, string $email, string $address)
{
    $query = "INSERT INTO auctionDatabase.Users(accountType, first_name, last_name, username, email, password, address) VALUES (?, ?, ?, ?, ?, ?, ?);";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $options = [
        'cost' => 12
    ];
    $hashedpassword = password_hash($password, PASSWORD_BCRYPT, $options); #Creates a hashed password and adds a cost to calculating it so to slow down hacking attempts.
    $stmt->bind_param("sssssss", $accountType, $firstname, $lastname, $username, $email, $hashedpassword, $address);
    $stmt->execute();
}