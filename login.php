<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staffID = $_POST['StaffID'];
    $password = $_POST['password'];

    // Attempt to retrieve the user from the database
    try {
        $stmt = $pdo->prepare("SELECT * FROM Staff WHERE StaffID = :staffID");
        $stmt->bindParam(':staffID', $staffID);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password. Use password_verify if passwords are hashed
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['StaffID'];
                $_SESSION['username'] = $user['FirstName'] . ' ' . $user['LastName'];
                $_SESSION['loggedIn'] = true;

                // Redirect user to the appropriate dashboard
                if ($user['Position'] === 'Instructor') {
                    $_SESSION['is_instructor'] = true;
                    header('Location: instructor_dashboard.php');
                } else {
                    // Assuming any other role is considered as Staff/Admin
                    $_SESSION['is_admin'] = true;
                    header('Location: staff_dashboard.php');
                }
                exit();
            } else {
                $error_message = "Invalid Username or Password!";
            }
        } else {
            $error_message = "Invalid Username or Password!";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <form method="post" action="login.php">
        <h2>Login</h2>
        <div class="form-group">
            <label for="StaffID">Staff ID:</label>
            <input type="text" name="StaffID" id="StaffID" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Login">
        </div>
        <?php
        if ($error_message != '') {
            echo '<div class="error-message">' . htmlspecialchars($error_message) . '</div>';
        }
        ?>
    </form>
</div>
</body>
</html>
