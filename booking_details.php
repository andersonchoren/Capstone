<?php
// booking_details.php
session_start();
require_once "connect.php";

if (isset($_SESSION['lastBookingId'])) {
    $bookingId = $_SESSION['lastBookingId'];

    $sql = "SELECT * FROM Bookings WHERE BookingID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $bookingId);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                echo "Booking ID: " . $row['BookingID'] . "<br>";
                echo "Student ID: " . $row['StudentID'] . "<br>";
                // ... Display other booking details ...
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
} else {
    header("Location: enroll.php"); // If there's no booking ID in the session, redirect to enroll
    exit;
}
?>

