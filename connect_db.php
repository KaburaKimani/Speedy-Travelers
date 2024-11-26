<?php
// Database connection settings
$server = "localhost";
$serveruser = "root";
$serverpassword = "";
$db = "speedy_travellers__()";

// Establish the connection
$connection = mysqli_connect($server, $serveruser, $serverpassword, $db);

// Confirm connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// If connected, you can remove this line. It’s just to confirm connection.
echo "Connected successfully to the database.";
?>