<?php
// Database connection details
$servername = "localhost"; // Change this if your database is hosted remotely
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "speedy_travelers"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitizeInput($data, $conn) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Form submission logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitizeInput($_POST['name'], $conn);
    $email = sanitizeInput($_POST['email'], $conn);
    $password = $_POST['password']; // Raw password
    $confirmPassword = $_POST['confirmPassword']; // Confirm password

    $errors = [];

    // Validate name
    if (!preg_match("/^[A-Za-z\s]+$/", $name)) {
        $errors[] = "Name should contain only alphabets and spaces.";
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate password
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%?&])[A-Za-z\d@$!%?&]{8,}$/";
    if (!preg_match($passwordPattern, $password)) {
        $errors[] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // If no errors, insert into the database
    if (empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            // Redirect to the success page
            header("Location: passenger.html");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Show errors
        echo "<h3>Signup Errors:</h3>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
}

$conn->close();
?>
