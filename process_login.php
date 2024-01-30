<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to the database (replace with your database details)
    $conn = new mysqli("localhost", "your_db_username", "your_db_password", "your_db_name");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user is a staff member
    $stmt = $conn->prepare("SELECT id FROM login WHERE StaffID = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["username"] = $username;
        header("Location: staff_dashboard.php");
    } else {
        // Check if the user is an instructor
        $stmt = $conn->prepare("SELECT id FROM login WHERE InstructorID = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION["username"] = $username;
            header("Location: instructor_dashboard.php");
        } else {
            echo "Invalid username or password.";
        }
    }

    $stmt->close();
    $conn->close();
}
?>


