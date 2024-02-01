<?php
session_start();
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bookingId'])) {
    $bookingId = $_POST['bookingId'];
    $bookingDate = $_POST['bookingDate'];
    $status = $_POST['status'];
    $paymentConfirmed = $_POST['paymentConfirmed'];

    // Update the booking in the database
    $sql = "UPDATE Bookings SET BookingDate = ?, Status = ?, PaymentConfirmed = ? WHERE BookingID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $bookingDate, $status, $paymentConfirmed, $bookingId);

    if ($stmt->execute()) {
        // Redirect back to staff_dashboard.php with a success message
        $_SESSION['message'] = "Booking updated successfully";
        header("Location: staff_dashboard.php");
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request";
}
?>
