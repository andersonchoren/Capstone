<?php
session_start();
require_once "connect.php";

$bookings = [];
$error_message = "";

if (isset($_SESSION['user_id'])) { // Ensure the user is logged in
    $studentId = $_SESSION['user_id'];

    // SQL to fetch all bookings for the logged-in student, joining with the students table to get the student's name
    $sql = "SELECT b.BookingID, b.StudentID, s.firstname,br.BranchName,f.Model,c.CourseName,cs.ClassDate, b.BookingDate, b.Status, b.PaymentConfirmed
            FROM Bookings b
            INNER JOIN students s ON b.StudentID = s.StudentID
            INNER JOIN branches br ON b.BranchID = br.BranchID
            INNER JOIN fleet f ON b.VehicleID = f.VehicleID
            INNER JOIN courses c ON b.CourseID = c.CourseID
            INNER JOIN classschedules cs ON b.ScheduleID = cs.ScheduleID
            WHERE b.StudentID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $studentId); // Use the studentId from the session
        if ($stmt->execute()) {
            $result = $stmt->get_result();
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
    <ul>
        <li><a href="javascript:if (window.history.length > 1) { window.history.back(); } else { window.location.href = 'index.html'; }">Back to Previous Page</a></li>
    </ul>
</nav>

<section class="booking-details-section">
    <h2>Booking Details</h2>
    <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php elseif (!empty($bookings)): ?>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Branch Name</th>
                <th>Vehicle Model</th>
                <th>Course Name</th>
                <th>Class Date</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Payment Confirmed</th>
            </tr>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['BookingID']); ?></td>
                    <td><?php echo htmlspecialchars($booking['StudentID']); ?></td>
                    <td><?php echo htmlspecialchars($booking['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($booking['BranchName']); ?></td>
                    <td><?php echo htmlspecialchars($booking['Model']); ?></td>
                    <td><?php echo htmlspecialchars($booking['CourseName']); ?></td>
                    <td><?php echo htmlspecialchars($booking['ClassDate']); ?></td>
                    <td><?php echo htmlspecialchars($booking['BookingDate']); ?></td>
                    <td><?php echo htmlspecialchars($booking['Status']); ?></td>
                    <td><?php echo $booking['PaymentConfirmed'] ? 'Yes' : 'No'; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>
</section>

<footer>
    <p> 2024 Excel Driving School. All rights reserved.
        <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a>
    </p>
</footer>
</body>
</html>
