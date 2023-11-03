<?php

function connect_to_db(){
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'root';
    $db_name = 'auctionDataBase';
    return mysqli_connect($db_host, $db_user, $db_password, $db_name);
}

?>