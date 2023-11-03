<?php

declare(strict_types=1);


function get_username(mysqli $conn, string $username) {
    $query = "SELECT username FROM auctionDatabase.Users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();
    return $result;
}

function get_email(mysqli $conn, string $email) {
    $query = "SELECT username FROM auctionDatabase.Users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();
    return $result;
}

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
    $hashedpassword = password_hash($password, PASSWORD_BCRYPT, $options);
    $stmt->bind_param("sssssss", $accountType, $firstname, $lastname, $username, $email, $hashedpassword, $address);
    $stmt->execute();
}