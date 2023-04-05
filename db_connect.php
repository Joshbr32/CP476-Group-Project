<?php
function get_database_credentials()
{
    include "db_config.php";
    return [
        "servername" => "localhost",
        "dbname" => "CP476",
        "username" => $_SESSION["username"],
        "password" => $_SESSION["password"],
    ];
}

function connect_to_database()
{
    $credentials = get_database_credentials();
    $servername = $credentials["servername"];
    $dbname = $credentials["dbname"];
    $username = $credentials["username"];
    $password = $credentials["password"];

    // Connect to the SQL server using MySQLi
    try {
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
    } catch (Exception $e) {
        $_SESSION["Database_Error"] = true;
        return null;
    }

    return $conn;
}


?>
