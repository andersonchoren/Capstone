<?php
session_start();
require_once "connect.php";

// Check if the user is logged in and has the role of staff
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header('Location: login.php'); // Redirect to login page
    exit();
}

// Fetch bookings
$sql = "SELECT BookingID, StudentID, BookingDate, Status, PaymentConfirmed FROM Bookings";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="image/logo..JPEG" alt="Excel Driving School Logo" style="width:15%; height: auto;">
        <h1>Excel Driving School - Staff Dashboard</h1>
    </div>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! (Staff)</p>
</header>

<nav>
    <ul>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div id="bookings" class="dashboard-section">
    <h2>Students Bookings</h2>
    <?php
    if ($result->num_rows > 0) {
        echo "<table>"; // Start a table
        echo "<tr><th>Booking ID</th><th>Student ID</th><th>Booking Date</th><th>Status</th><th>Payment Confirmed</th><th>Actions</th></tr>";
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["BookingID"]). "</td>";
            echo "<td>" . htmlspecialchars($row["StudentID"]). "</td>";
            echo "<td>" . htmlspecialchars($row["BookingDate"]). "</td>";
            echo "<td>" . htmlspecialchars($row["Status"]). "</td>";
            echo "<td>" . ($row["PaymentConfirmed"] ? 'Yes' : 'No') . "</td>";
            // Edit link (pass the BookingID as a GET parameter)
            echo "<td><a href='booking_edit.php?bookingId=" . $row["BookingID"] . "'>Edit</a></td>";
            echo "</tr>";
        }
        echo "</table>"; // Close the table
    } else {
        echo "0 results";
    }
    ?>
</div>

<!-- Implement similar structures for Schedules, Reminders, Invoices, Payments -->

<footer>
    <p>&copy; <?php echo date("Y"); ?> Excel Driving School. All rights reserved.
        <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a>
    </p>
</footer>
</body>
</html>
