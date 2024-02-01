<?php
session_start();
require_once "connect.php"; // Your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM login WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                // Verify the password (consider using password hashing in your database and password_verify() in PHP)
                if ($password === $row['password']) {
                    // Set session variables
                    $_SESSION['username'] = $username;
                    if ($row['StaffID']) {
                        $_SESSION['role'] = 'staff';
                        $_SESSION['userId'] = $row['StaffID'];
                        header("Location: staff_dashboard.php"); // Redirect to staff dashboard
                    } elseif ($row['InstructorID']) {
                        $_SESSION['role'] = 'instructor';
                        $_SESSION['userId'] = $row['InstructorID'];
                        header("Location: instructor_dashboard.php"); // Redirect to instructor dashboard
                    }
                    exit();
                } else {
                    $error_message = "Invalid username or password.";
                }
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Error preparing statement: " . $conn->error;
    }
    $conn->close();
} else {
    $error_message = "Please fill both username and password.";
}

if (isset($error_message)) {
    echo "<p>Error: $error_message</p>";
    echo "<p><a href='login.php'>Try again</a></p>";
}
?>

