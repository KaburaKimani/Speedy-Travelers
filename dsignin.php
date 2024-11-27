<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "speedy_travelers";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitizeInput($_POST['name'], $conn);
    $email = sanitizeInput($_POST['email'], $conn);
    $password = sanitizeInput($_POST['password'], $conn);
    $license = sanitizeInput($_POST['license'], $conn);

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($license)) {
        echo "<p style='color: red;'>All fields are required.</p>";
    } else {
        // Check if the email or license already exists
        $checkQuery = "SELECT * FROM drivers WHERE email = '$email' OR license = '$license'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows > 0) {
            echo "<p style='color: red;'>Email or Driver's License already exists. Please use a different one.</p>";
        } else {
            // Insert the driver's details into the database
            $sql = "INSERT INTO drivers (name, email, password, license) 
                    VALUES ('$name', '$email', '$hashedPassword', '$license')";

            if ($conn->query($sql) === TRUE) {
                // Redirect to passenger.html after successful sign-up
                header("Location: passenger.html");
                exit();
            } else {
                echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
        }
    }
}

// Close the connection
$conn->close();
?>
