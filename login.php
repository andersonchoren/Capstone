<?php
require_once "connect.php";

// Start session
session_start();

// Include your database connection here
// require 'db_connection.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate login credentials
    // You should retrieve user data from the database and verify the password
    // This is just a placeholder logic
    if ($username == 'admin' && $password == 'password') {
        $_SESSION['user_id'] = 1; // Example user ID
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = true;
        header('Location: index.php');
        exit();
    } else {
        $error_message = 'Invalid Username or Password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... head elements ... -->
    <title>Login - Admin</title>
</head>
<body>
    <form method="post" action="login.php">
        <h2>Admin Login</h2>
        <p>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <input type="submit" value="Login">
        </p>
        <?php
        if ($error_message != '') {
            echo '<p>' . $error_message . '</p>';
        }
        ?>
    </form>
</body>
</html>
