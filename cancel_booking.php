<?php
session_start();
require_once "connect.php";

// Check if the user is logged in and has the role of staff
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header('Location: login.php'); // Redirect to login page
    exit();
}

if (isset($_GET['bookingId'])) {
    $bookingId = $_GET['bookingId'];

    // SQL to cancel the booking
    $sql = "UPDATE bookings SET Status = 'Cancelled' WHERE BookingID = ? AND Status != 'Cancelled'"; // Make sure it's not already cancelled

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $bookingId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Booking cancelled successfully.";
        } else {
            $_SESSION['message'] = "Failed to cancel booking or booking already cancelled.";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Error in database query.";
    }

    // Redirect back to the dashboard or the booking page
    header('Location: staff_dashboard.php');
    exit();
} else {
    $_SESSION['message'] = "Invalid request.";
    header('Location: staff_dashboard.php'); // Redirect if the bookingId wasn't set in the URL
    exit();
}

$conn->close();
?>