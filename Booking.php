<?php
// booking.php
session_start();
require_once "connect.php"; // Make sure this file has the correct database connection setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentId = $_SESSION['user_id']; // Ensure the student's ID is stored in the session when they log in
    $scheduleId = $_POST['schedule'];

    // Default values
    $bookingDate = date("Y-m-d");
    $status = "Pending";
    $paymentConfirmed = 0;

    // Prepare an insert statement
    $sql = "INSERT INTO Bookings (StudentID, ScheduleID, BookingDate, Status, PaymentConfirmed) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iissi", $studentId, $scheduleId, $bookingDate, $status, $paymentConfirmed);
        if ($stmt->execute()) {
            $_SESSION['lastBookingId'] = $conn->insert_id; // Store the last inserted ID in the session
            header("Location: payment.php"); // Redirect to payment page
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
} else {
    header("Location: enroll.php"); // Redirect back to the enroll page if the form was not submitted
    exit;
}
?>
