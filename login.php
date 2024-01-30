<?php
require_once "connect.php"; // This file should contain your database connection details

// Start session
session_start();

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staffID = $_POST['StaffID'];
    $password = $_POST['password'];

    // Attempt to retrieve the user from the database
    try {
        $stmt = $pdo->prepare("SELECT * FROM Staff WHERE StaffID = :staffID");
        $stmt->bindParam(':staffID', $staffID);
        $stmt->execute();

        // Check if user exists
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password (consider storing hashed passwords and using password_verify)
            if ($password == $user['password']) { // Replace this line if you're using hashed passwords
                $_SESSION['user_id'] = $user['StaffID'];
                $_SESSION['username'] = $user['FirstName'] . ' ' . $user['LastName'];
                $_SESSION['is_admin'] = ($user['Position'] == 'Manager'); // Example condition for admin

                header('Location: index.php');
                exit();
            } else {
                $error_message = 'Invalid Username or Password!';
            }
        } else {
            $error_message = 'Invalid Username or Password!';
        }
    } catch (PDOException $e) {
        $error_message = 'Error: ' . $e->getMessage();
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
