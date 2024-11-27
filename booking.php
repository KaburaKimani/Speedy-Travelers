<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "speedy_travelers";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitizeInput($data, $conn) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from = sanitizeInput($_POST['from'], $conn);
    $to = sanitizeInput($_POST['to'], $conn);
    $date = sanitizeInput($_POST['date'], $conn);
    $time = sanitizeInput($_POST['time'], $conn);

    // Validate form inputs
    if (empty($from) || empty($to) || empty($date) || empty($time)) {
        echo "<p style='color: red;'>All fields are required. Please fill out the form completely.</p>";
    } else {
        // Insert booking information into the database
        $sql = "INSERT INTO bookings (start_location, end_location, travel_date, travel_time) 
                VALUES ('$from', '$to', '$date', '$time')";

        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>Booking successful! Your journey from $from to $to on $date at $time has been recorded.</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }
}

// Close the connection
$conn->close();
?>
