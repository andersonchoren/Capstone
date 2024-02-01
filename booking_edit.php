<?php
session_start();
require_once "connect.php";

if (isset($_GET['bookingId'])) {
    $bookingId = $_GET['bookingId'];

    $sql = "SELECT * FROM Bookings WHERE BookingID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $bookingId);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                // Variables for display, ensure to escape the output
                $bookingDate = htmlspecialchars($row['BookingDate']);
                $status = htmlspecialchars($row['Status']);
                $paymentConfirmed = $row['PaymentConfirmed'];
            } else {
                $error_message = "No booking found for the given ID.";
            }
        } else {
            $error_message = "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Error preparing statement: " . $conn->error;
    }
    $conn->close();
} else {
    $error_message = "No Booking ID provided.";
}

// HTML form for editing the booking
if (!isset($error_message)) {
    // Display form with booking details for editing
    echo "<form action='update_booking.php' method='post'>";
    echo "<input type='hidden' name='bookingId' value='$bookingId'>";
    echo "<label for='bookingDate'>Booking Date:</label>";
    echo "<input type='date' name='bookingDate' value='$bookingDate'><br>";
    echo "<label for='status'>Status:</label>";
    echo "<input type='text' name='status' value='$status'><br>";
    echo "<label for='paymentConfirmed'>Payment Confirmed:</label>";
    echo "<select name='paymentConfirmed'>";
    echo $paymentConfirmed ? "<option value='1' selected>Yes</option><option value='0'>No</option>" :
        "<option value='1'>Yes</option><option value='0' selected>No</option>";
    echo "</select><br>";
    echo "<input type='submit' value='Update Booking'>";
    echo "</form>";
} else {
    // Display error message
    echo "<p class='error'>$error_message</p>";
}
?>
