<?php
session_start();  // Start the session at the beginning of the script

require_once "connect.php";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Prevent SQL Injection
    $password = $_POST['password'];

    $sql = "SELECT * FROM Students WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Login successful, set session variables
            $_SESSION['user_id'] = $row['StudentID'];
            $_SESSION['username'] = $row['username'];

            // Redirect to another page
            header("Location: dashboard.php"); // Replace 'dashboard.php' with the actual page
            exit;
        } else {
            // Invalid password
            echo "Invalid username or password";
        }
    } else {
        // Username not found
        echo "Invalid username or password";
    }

    $stmt->close();
} else {
    // Username or password not provided in the form
    echo "Username or password not provided.";
}

$conn->close();
?>

