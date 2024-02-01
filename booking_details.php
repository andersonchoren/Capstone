<?php
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
                // Prepare variables from the row for display (ensure to escape output)
                $bookingId = htmlspecialchars($row['BookingID']);
                $studentId = htmlspecialchars($row['StudentID']);
                // ... Other variables ...
                $bookingDate = htmlspecialchars($row['BookingDate']);
                $status = htmlspecialchars($row['Status']);
                $paymentConfirmed = $row['PaymentConfirmed'] ? 'Yes' : 'No';
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
    $error_message = "Invalid access or no booking found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - Excel Driving School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="image/logo..JPEG" alt="Excel Driving School Logo">
        <h1>Excel Driving School</h1>
    </div>
    <p>Your Journey Begins Here</p>
</header>

<nav>
    <<ul>
        <li><a href="javascript:if (window.history.length > 1) { window.history.back(); } else { window.location.href = 'index.html'; }">Back to Previous Page</a></li>
    </ul>
</nav>

<section class="booking-details-section">
    <h2>Booking Details</h2>
    <?php if (isset($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php else: ?>
        <p>Booking : <?php echo $bookingId; ?></p>
        <p>Student : <?php echo $studentId; ?></p>
        <!-- ... Other details ... -->
        <p>Booking Date: <?php echo $bookingDate; ?></p>
        <p>Status: <?php echo $status; ?></p>
        <p>Payment Confirmed: <?php echo $paymentConfirmed; ?></p>
    <?php endif; ?>
</section>

<footer>
    <p> 2024 Excel Driving School. All rights reserved.
        <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a>
    </p>
</footer>
</body>
</html>


