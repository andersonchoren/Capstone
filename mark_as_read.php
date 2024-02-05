<?php
session_start();
require_once "connect.php";

// Check if the user is logged in and has the role of student
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header('Location: student_login.php'); // Redirect to login page
    exit();
}

if (isset($_GET['notificationId'])) {
    $notificationId = $_GET['notificationId'];
    $studentId = $_SESSION['userId']; // Assuming student's user ID is in the session

    // Prepare the SQL statement to update the notification
    $sql = "UPDATE Notifications SET IsRead = 1 WHERE NotificationID = ? AND StudentID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $notificationId, $studentId);

        if ($stmt->execute()) {
            // If successful, redirect back to the student area or a specific page
            $_SESSION['message'] = "Notification marked as read successfully!";
            header('Location: student-area.html');
        } else {
            // Handle errors
            $_SESSION['message'] = "Error: " . $stmt->error;
            header('Location: student-area.html');
        }

        $stmt->close();
    } else {
        // Handle errors
        $_SESSION['message'] = "Error: " . $conn->error;
        header('Location: student-area.html');
    }
} else {
    // If the notificationId isn't set, redirect back or handle the error
    $_SESSION['message'] = "Error: No notification ID provided.";
    header('Location: student-area.html');
}

$conn->close();
?>

