<?php
session_start();
require_once "connect.php";

// CSRF protection (if you have a token generated in your form)
// if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//     die('CSRF token validation failed');
// }

if (isset($_POST['notificationId'], $_SESSION['Studentid'])) {
    $notificationId = $_POST['notificationId'];
    $studentId = $_SESSION['Studentid']; // Use the same session variable name as in your other scripts
    $isRead = 1; // Value to indicate the notification is read

    // First, check if the notification belongs to the student
    $checkSql = "SELECT StudentID FROM Notifications WHERE NotificationID = ?";
    if ($checkStmt = $conn->prepare($checkSql)) {
        $checkStmt->bind_param("i", $notificationId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $checkStmt->close();

        if ($checkResult->num_rows === 1) {
            $notification = $checkResult->fetch_assoc();
            if ($notification['StudentID'] == $studentId) {
                // The notification belongs to the student; proceed to mark as read
                $sql = "UPDATE Notifications SET IsRead = ? WHERE NotificationID = ?";

                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ii", $isRead, $notificationId);

                    if ($stmt->execute()) {
                        $_SESSION['message'] = "Notification marked as read successfully!";

                        // Insert a record into ReadNotifications table
                        $insertSql = "INSERT INTO ReadNotifications (NotificationID, StudentID, DateRead) VALUES (?, ?, NOW())";
                        if ($insertStmt = $conn->prepare($insertSql)) {
                            $insertStmt->bind_param("ii", $notificationId, $studentId);
                            $insertStmt->execute();
                            $insertStmt->close();
                        }
                    } else {
                        $_SESSION['message'] = "Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    $_SESSION['message'] = "Error: " . $conn->error;
                }
            } else {
                $_SESSION['message'] = "You do not have permission to mark this notification as read.";
            }
        } else {
            $_SESSION['message'] = "Notification not found.";
        }
    }
    $conn->close();
} else {
    $_SESSION['message'] = "Notification ID or User ID not set.";
}

// Redirect back to the student-area.html page
header('Location: student-area.html');
exit();
?>
