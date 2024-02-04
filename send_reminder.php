<?php
session_start();
require_once "connect.php";

// Check if the user is logged in and has the role of staff
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header('Location: login.php'); // Redirect to login page
    exit();
}

if (isset($_GET['bookingId']) && isset($_GET['studentId'])) {
    $bookingId = $_GET['bookingId'];
    $studentId = $_GET['studentId'];

    // Fetch student email and booking details
    $sql = "SELECT st.Email as StudentEmail, bk.ClassDate, cls.StartTime 
            FROM students st 
            INNER JOIN bookings bk ON st.StudentID = bk.StudentID 
            INNER JOIN classschedules cls ON bk.ScheduleID = cls.ScheduleID 
            WHERE bk.BookingID = ? AND st.StudentID = ?";

    // Prepare and bind parameters
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $bookingId, $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $studentEmail = $row['StudentEmail'];
            $classDate = $row['ClassDate'];
            $startTime = $row['StartTime'];

            // Send Email Logic (Simulation)
            // Normally you would use a mailer library or API to send the email.
            $to = $studentEmail;
            $subject = "Class Reminder";
            $message = "Hello, just a reminder about your upcoming class on $classDate at $startTime.";
            $headers = "From: noreply@exceldrivingschool.com";

            if (mail($to, $subject, $message, $headers)) {
                echo "Reminder sent successfully to $to";
                // Optionally, you might want to log this in the database.
            } else {
                echo "Failed to send reminder.";
            }
        } else {
            echo "No booking found.";
        }
        $stmt->close();
    } else {
        echo "Error in database query.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
