<?php
session_start(); // start the session

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the password field
    if (!isset($_POST["password"]) || empty($_POST["password"])) {
        $_SESSION["error"] = "Please enter a password.";
        header("Location: index.php");
        exit();
    }

    // Check if the password is correct
    $password = $_POST["password"];
    if ($password != "1qazxcvb") {
        $_SESSION["error"] = "Incorrect password.";
        header("Location: index.php");
        exit();
    }

    // Password is correct, do something here (e.g. set a session variable, redirect to another page)
    $_SESSION["authenticated"] = true;
    header("Location: home.php");
    exit();

}
?>