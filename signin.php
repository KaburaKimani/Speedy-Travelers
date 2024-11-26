<?php
session_start();

// Dummy user data for demonstration purposes
$users = [
    ['email' => 'user@example.com', 'password' => 'password123']
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists and the password is correct
    foreach ($users as $user) {
        if ($user['email'] == $email && $user['password'] == $password) {
            $_SESSION['user'] = $user;
            header("Location: home.html");
            exit();
        }
    }

    // If login fails, set an error message
    $error = "Invalid email or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Speedy Travelers</title>
    <link rel="stylesheet" href="signin.css">
</head>
<body class="login-body">
    <img src="logo-color.png" alt="Speedy Travelers Logo" class="logo">
    <h1>LOGIN</h1>
    <div>
        <form action="signin.php" method="POST" class="login-form">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter Email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter Password" required>

            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
