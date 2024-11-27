<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "speedy_travelers"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

<?php
session_start();

// Dummy user data for demonstration purposes
$users = [
    ['email' => 'user@example.com', 'password' => 'password123']
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if the email is already registered
        foreach ($users as $user) {
            if ($user['email'] == $email) {
                $error = "Email is already registered.";
                break;
            }
        }

        // If no errors, register the user
        if (!isset($error)) {
            $users[] = ['email' => $email, 'password' => $password];
            $_SESSION['user'] = ['email' => $email, 'password' => $password];
            header("Location: home.html");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | Speedy Travelers</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body class="signup-body">
    <img src="logo-color.png" alt="Speedy Travelers Logo" class="logo">
    <h1>SIGN UP</h1>
    <div>
        <form action="signup.php" method="POST" class="signup-form">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter Email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter Password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>

            <button type="submit">Sign Up</button>
        </form>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
