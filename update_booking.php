<?php
session_start();
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bookingId'])) {
    $bookingId = $_POST['bookingId'];
    $studentId = $_POST['studentId']; // Fetch other form data...
    $bookingDate = $_POST['bookingDate'];
    $status = $_POST['status'];
    $paymentConfirmed = $_POST['paymentConfirmed'];

    // Prepare your update statement
    $sql = "UPDATE Bookings SET StudentID = ?, BookingDate = ?, Status = ?, PaymentConfirmed = ? WHERE BookingID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issii", $studentId, $bookingDate, $status, $paymentConfirmed, $bookingId);

    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the booking details page or display a message
    header("Location: booking_details.php?bookingId=$bookingId");
} else {
    echo "Invalid request";
}
?>
