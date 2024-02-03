<?php
session_start();
require_once "connect.php";

if (isset($_SESSION['user_id'])) { // Ensure the user is logged in
    $studentId = $_SESSION['user_id'];

    // SQL to fetch all bookings for the logged-in student
    $sql = "SELECT * FROM Bookings WHERE StudentID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $studentId); // Use the studentId from the session
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $bookings = [];
            while ($row = $result->fetch_assoc()) {
                array_push($bookings, $row);
            }
            if (count($bookings) == 0) {
                $error_message = "No bookings found for the student.";
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
    $error_message = "Invalid access or no student ID found.";
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
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Student ID</th>
                <!-- ... Other headers ... -->
                <th>Booking Date</th>
                <th>Status</th>
                <th>Payment Confirmed</th>
            </tr>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['BookingID']); ?></td>
                    <td><?php echo htmlspecialchars($booking['StudentID']); ?></td>
                    <!-- ... Other details ... -->
                    <td><?php echo htmlspecialchars($booking['BookingDate']); ?></td>
                    <td><?php echo htmlspecialchars($booking['Status']); ?></td>
                    <td><?php echo $booking['PaymentConfirmed'] ? 'Yes' : 'No'; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>

<footer>
    <p> 2024 Excel Driving School. All rights reserved.
        <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a>
    </p>
</footer>
</body>
</html>


