<?php

function connectToDB($dbname=null) {

    $servername = "localhost";
    $username = "root";
    $password = "zYSW1955h5Zw";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        return NULL;

    }

    return $conn;
}
?>
