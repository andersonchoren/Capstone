<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to the database (using the provided credentials)
    $servername = "localhost"; // Your MySQL server hostname
    $dbUsername = "root"; // Your MySQL database username
    $dbPassword = ""; // Your MySQL database password (blank in this case)
    $dbname = "ExcelDrivingSchool"; // Your MySQL database name

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user is a staff member
    // Check if the user is a staff member
    $stmt = $conn->prepare("SELECT id FROM login WHERE StaffID = ? AND password = ?");
    if (!$stmt) {
        die("Query error: " . mysqli_error($conn));
    }
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["username"] = $username;
        header("Location: staff_dashboard.php");
        exit();
    } else {
        // Check if the user is an instructor
        $stmt = $conn->prepare("SELECT id FROM login WHERE InstructorID = ? AND password = ?");
        if (!$stmt) {
            die("Query error: " . mysqli_error($conn));
        }
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION["username"] = $username;
            header("Location: instructor_dashboard.php");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    }

    $stmt->close();
    $conn->close();

}
?>


