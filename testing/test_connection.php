<?php
$servername = "localhost";
$username = "cp476";
$password = "4v8b3gVScQpwm4CV";
$database = "CP476";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
